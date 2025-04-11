var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      page_list: true,
      page_error: false,
      page_form: false,
      page_spinner: false,

      page_title: "Administracion de Personal",
      page_current: 1,
      page_items: 30,

      staff_search: "",
      staff_profile: {},
      staff_listed: {},
      staff_leaked: {},
      staff_form: {},
    };
  },
  mounted: function () {
    this.staff_list();
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
    page_error: function () {
      if (this.page_error) {
        this.page_spinner = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    page_spinner: function () {
      if (this.page_spinner) {
        this.page_error = false;
        this.page_list = false;
        this.page_form = false;
      }
    },
    staff_search: function () {
      this.staff_filter();
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
                this.staff_form.person_picture = res.data;
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
    staff_list: function () {
      this.page_spinner = true;
      const url = `${uri}staff/staff_store`;
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.staff_profile = res.data.staff_profile;
              this.staff_listed = res.data.staff_listed;
              this.staff_filter();
              this.page_list = true;
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición listarstaff ${error}`, "bg-danger");
        });
    },
    staff_filter: function () {
      let array_list = this.staff_listed;
      let text = this.staff_search.toLowerCase();
      // Filtros en array
      array_list = array_list.filter((item) => item.keywords.match(text));
      // Array filtrado
      if (!array_list.length) return (this.staff_leaked = false);
      this.staff_leaked = array_list;
      this.page_list = true;
    },
    staff_new: function () {
      this.page_spinner = true;
      const url = `${uri}staff/staff_new`;
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.staff_form = res.data.staff_form;
              this.staff_form.edad = calculate_age(this.staff_form.person_birthday);
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`staff_new ${error}`, "bg-danger");
        });
    },
    staff_view: function (staff) {
      let data = new FormData();
      const url = `${uri}staff/staff_view`;
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(staff));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              this.staff_form = res.data.staff_form;
              this.staff_form.edad = calculate_age(this.staff_form.person_birthday);
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición viewstaff ${error}`, "bg-danger");
        });
    },
    staff_delete: function (staff) {
      Swal.fire({
        text: `¿Seguro desea eliminar el personal seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          staff.spinner = true;
          const url = `${uri}staff/staff_delete`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    this.staff_list();
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
              toastr(`Hubo un error en la petición articleDelete ${error}`, "bg-danger");
            });
        }
      });
    },
    staff_save: function () {
      if (!this.staff_form.person_document) {
        toastr("Debe completar campo DNI.", "bg-danger");
        return;
      }
      if (!this.staff_form.person_name) {
        toastr("Debe completar campo Nombre.", "bg-danger");
        return;
      }
      if (!this.staff_form.person_cellphone) {
        toastr("Debe completar campo Celular.", "bg-danger");
        return;
      }
      if (this.staff_form.person_cellphone.length < 10) {
        toastr("Celular invalido", "bg-danger");
        return;
      }
      this.page_spinner = true;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(this.staff_form));

      if (this.staff_form.person_id != "") {
        url = `${uri}staff/staff_update`;
      } else {
        url = `${uri}staff/staff_add`;
      }

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                toastr(res.msg, "bg-success");
                this.staff_list();
              }
              break;
            case 400:
              toastr(res.msg, "bg-danger");
              this.page_form = true;
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`staff_save ${error}`, "bg-danger");
        });
    },
    staff_status: function (staff) {
      staff.spinner = true;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(staff));

      url = `${uri}staff/staff_status`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                Object.assign(staff, res.data.staff_form);
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición statusstaff ${error}`, "bg-danger");
        });
    },
    staff_pass: function (staff) {
      Swal.fire({
        text: `¿Seguro desea resetear contraseña de personal seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, resetear",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          console.time();
          staff.spinner = true;
          let data = new FormData();
          url = `${uri}staff/staff_pass`;
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));
          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    toastr(res.msg, "bg-success");
                    staff.spinner = false;
                    console.timeEnd();
                  }
                  break;
                default:
                  get_action(res.status, res.msg);
                  this.page_error = true;
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición statusstaff ${error}`, "bg-danger");
            });
        }
      });
    },
    access_save: function (staff) {
      staff.spinner = true;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(staff));

      url = `${uri}staff/access_save`;
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                toastr(res.msg, "bg-success");
                staff.spinner = false;
              }
              break;
            default:
              get_action(res.status, res.msg);
              this.page_error = true;
              break;
          }
        })
        .catch(function (error) {
          toastr(`profile_save ${error}`, "bg-danger");
        });
    },
  },
});
