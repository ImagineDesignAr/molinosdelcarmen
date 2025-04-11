var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      page_form: true,
      page_error: false,
      page_spinner: false,

      page_title: "Nueva Sucursal",
      page_options: {},
      store_form: {},
    };
  },
  mounted: function () {
    this.store_form = { store_picture: urlimages + "_local.png", store_type: "store" };
  },
  watch: {
    page_form: function () {
      if (this.page_form) {
        this.page_spinner = false;
        this.page_error = false;
      }
    },
    page_error: function () {
      if (this.page_error) {
        this.page_form = false;
        this.page_spinner = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_form = false;
        this.page_error = false;
      }
    },
  },
  computed: {},
  methods: {
    picture_upload: function () {
      const url_upload = get_meta("urlupload");
      const filename = this.$refs.main_picture.files[0];

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
                this.store_form.store_picture = res.data;
                toastr(res.msg, "bg-success");
              }
              break;
            default:
              get_action(res.status, res.msg);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición main_picture_upload ${error}`, "bg-danger");
        });
    },
    save_store: function () {
      if (!this.store_form.store_name) {
        toastr("Debe completar Nombre.", "bg-danger");
        return;
      }
      if (!this.store_form.store_address) {
        toastr("Debe completar campo Direccion.", "bg-danger");
        return;
      }
      this.page_spinner = true;
      url = `${uri}stores/store_add`;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(this.store_form));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                toastr(res.msg, "bg-success");
                this.store_form = { store_picture: urlimages + "_local.png", store_type: "store" };
                this.page_form = true;
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`save_store ${error}`, "bg-danger");
        });
    },
  },
});
