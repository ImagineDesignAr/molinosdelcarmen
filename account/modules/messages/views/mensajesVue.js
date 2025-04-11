var vm = new Vue({
  el: "#app",
  data: {
    page_list: false,
    page_spinner: true,
    page_error: false,
    page_title: "Administracion de mensajes",
    page_current: 1,
    page_items: 10,

    message_search: "",
    message_listed: [],
    message_leaked: null,
    message_form: null,
  },
  mounted: function () {
    this.message_list();
  },
  watch: {
    page_list: function () {
      if (this.page_list) {
        this.page_spinner = false;
        this.page_error = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_error = false;
        this.page_list = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_spinner = false;
        this.page_list = false;
      }
    },
  },
  computed: {},
  methods: {
    message_delete: function (seleccion) {
      Swal.fire({
        text: `¿Seguro desea eliminar el mensaje seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${uri}messages/message_delete`;
          let data = new FormData();

          data.append("csrf", csrf);
          data.append("form", JSON.stringify(seleccion));

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    this.message_listed = res.data.message_listed;
                    this.message_filter();
                    toastr(res.msg, "bg-success");
                  }
                  break;
                default:
                  get_action(res.status, res.msg);
                  this.page_error = true;
                  break;
              }
            })
            .catch(function (error) {
              toastr(`delete_messages ${error}`, "bg-danger");
            });
        }
        this.page_list = true;
      });
    },
    message_view: function (seleccion) {
      this.message_form = seleccion;
    },
    message_list: function () {
      const url = `${uri}messages/message_list`;
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.message_listed = res.data.message_listed;
              this.message_filter();
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`message_list ${error}`, "bg-danger");
        });
    },
    message_filter: function () {
      let array_list = this.message_listed;
      if (!array_list.length) return (this.message_leaked = false);
      this.message_leaked = array_list;
    },
  },
});
