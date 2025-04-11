var vm = new Vue({
  el: "#app",
  data: {
    page_list: false,
    page_form: false,
    page_spinner: true,
    page_error: false,
    page_title: "Administracion de noticias",
    page_current: 1,
    page_items: 10,

    blog_search: "",
    blog_listed: {},
    blog_leaked: null,
    blog_form: null,
  },
  mounted: function () {
    this.blog_list();
  },
  watch: {
    page_list: function () {
      if (this.page_list) {
        this.page_spinner = false;
        this.page_error = false;
        this.page_form = false;
      }
    },
    page_form: function () {
      if (this.page_form) {
        this.page_spinner = false;
        this.page_error = false;
        this.page_list = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_error = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_spinner = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    blog_search() {
      this.blog_filter();
    },
  },
  computed: {},
  methods: {
    picture_upload: function () {
      const url_upload = getMeta("urlupload");
      const filename = this.$refs.profile_picture.files[0];

      let data = new FormData();
      data.append("csrf", csrf);
      data.append("filename", filename);

      axios
        .post(url_upload, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.blog_form.blog_picture = res.data;
                toastr(res.msg, "bg-success");
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición uploadImage ${error}`, "bg-danger");
        });
    },
    blog_new: function () {
      this.page_spinner = true;
      let data = new FormData();
      const url = `${uri}blogs/blog_new`;
      data.append("csrf", csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.blog_form = res.data.blog_form;
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`blog_new ${error}`, "bg-danger");
        });
    },
    blog_delete: function (seleccion) {
      Swal.fire({
        text: `¿Seguro desea eliminar la item seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${uri}blogs/blog_delete`;
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
                    this.blog_listed = res.data.blog_listed;
                    this.blog_filter();
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
              toastr(`blog_delete ${error}`, "bg-danger");
            });
        }
      });
    },
    blog_save: function () {
      if (!this.blog_form.blog_date) {
        toastr("Debe completar campo Fecha.", "bg-danger");
        return;
      }
      if (!this.blog_form.blog_title) {
        toastr("Debe completar campo Titulo.", "bg-danger");
        return;
      }
      if (!this.blog_form.blog_content) {
        toastr("Debe completar campo contenido.", "bg-danger");
        return;
      }

      this.page_spinner = true;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(this.blog_form));

      if (this.blog_form.blog_id != "") {
        url = `${uri}blogs/blog_update`;
      } else {
        url = `${uri}blogs/blog_add`;
      }

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.blog_list();
                toastr(res.msg, "bg-success");
              }
              break;
            case 400:
              if (res.data != []) {
                toastr(res.msg, "bg-danger");
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`blog_save ${error}`, "bg-danger");
        });
    },
    blog_status: function (seleccion) {
      this.page_spinner = true;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(seleccion));

      url = `${uri}blogs/set_condition`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.blog_list();
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`blog_status ${error}`, "bg-danger");
        });
    },
    blog_view: function (seleccion) {
      this.page_spinner = true;

      const url = `${uri}blogs/blog_view`;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(seleccion));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.blog_form = res.data.blog_form;
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`blog_view ${error}`, "bg-danger");
        });
    },
    blog_list: function () {
      const url = `${uri}blogs/blog_list`;
      let data = new FormData();

      data.append("csrf", csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.blog_listed = res.data.blog_listed;
              this.blog_filter();
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`blog_list ${error}`, "bg-danger");
        });
    },
    blog_filter: function () {
      let array_list = this.blog_listed;
      let text = this.blog_search.toLowerCase();
      // Filtros en array
      array_list = array_list.filter((item) => item.keywords.match(text));
      console.log(array_list);
      // Array filtrado
      if (!array_list.length) return (this.blog_leaked = false);
      this.blog_leaked = array_list;
    },
    blog_close: function () {
      this.blog_form = null;
      this.page_list = true;
    },
  },
});
