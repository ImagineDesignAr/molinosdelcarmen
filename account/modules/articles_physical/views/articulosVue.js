var vm = new Vue({
  el: "#app",
  data: function () {
    return {
      page_title: "Escritorio de Articulos",
      page_current: 1,
      page_items: 10,
      page_list: true,
      page_form: false,
      page_spinner: false,
      page_error: false,

      control_form: "form-control-sm",
      control_btn: "btn-sm",
      btn_save: true,

      brand_input: "",
      brand_listed: [],
      brand_new: false,

      category_input: "",
      category_listed: [],
      category_selected: "all",

      brand_selected: "all",

      drafts_lists: [],

      physical_search: "",
      physical_listed: [],
      physical_leaked: [],
      physical_form: [],
    };
  },
  mounted: function () {
    this.category_list();
    this.brand_list();
    this.drafts_list();
    this.physical_list();
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
        topFunction();
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
    physical_search: function () {
      this.physical_filter();
    },
  },
  computed: {},
  methods: {
    refresh: function () {
      this.brand_new = false;
      this.brand_list();
      this.drafts_list();
      this.physical_list();
    },
    /* FUNCIONES GLOBALES */
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
                this.physical_form.physical_picture = res.data;
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
    calculate_price: function () {
      const { physical_cost, physical_tax, physical_utility, physical_automatic_price } = this.physical_form;
      let costo = comprobar_nan(physical_cost);
      if (physical_automatic_price) {
        let calculate_utility = profitability(costo, comprobar_nan(physical_utility));
        let calculate_tax = profitability(costo + calculate_utility, comprobar_nan(physical_tax));

        this.physical_form.physical_price = (costo + calculate_utility + calculate_tax).toFixed(2);
      }
    },
    /* FUNCIONES GLOBALES */
    /* MANEJO DE TODO LO RELACIONADO A LOS ARTICULOS */
    article_available: function (physical) {
      let data = new FormData();

      data.append("csrf", csrf);
      data.append("form", JSON.stringify(physical));
      const url = `${uri}physical/article_available`;

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.physical_list();
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición articleNew ${error}`, "bg-danger");
        });
    },
    physical_filter: function () {
      let text = this.physical_search.trim().toLowerCase();
      this.physical_leaked = this.physical_listed.filter((item) => item.physical_metadata.includes(text) && (this.category_selected === "all" || item.category_id === this.category_selected) && (this.brand_selected === "all" || item.physical_brand_id === this.brand_selected));
      // Si no hay coincidencias, asigna `false`
      this.physical_leaked = this.physical_leaked.length > 0 ? this.physical_leaked : false;
      // Activa la paginación
      this.page_list = true;
    },
    physical_list: function () {
      this.page_spinner = true;
      const url = `${uri}physical/physicals_list`;
      let data = new FormData();
      data.append("csrf", csrf);
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.physical_listed = res.data.physical_listed;
                this.physical_filter();
                this.page_current = 1;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`physical_list ${error}`, "bg-danger");
        });
    },
    physical_new: function () {
      const url = `${uri}physical/physical_new`;
      let data = new FormData();
      data.append("csrf", csrf);
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.physical_form = res.data.physical_form;
                this.page_form = true;
                toastr(res.msg, "bg-success");
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`physical_new ${error}`, "bg-danger");
        });
    },
    physical_save: function () {
      if (!this.physical_form.physical_title) {
        toastr("Debe ingresar un titulo.", "bg-danger");
        return;
      }
      if (!this.physical_form.physical_metadata) {
        toastr("Debe ingresar al menos una metadata.", "bg-danger");
        return;
      }
      if (this.physical_form.physical_condition == 1) {
        // Si activan el articulo verifica requisitos basicos.
        if (!this.physical_form.category.length) {
          // Comprobar categorias
          toastr("Debe asignar categoria al articulo.", "bg-danger");
          return;
        }
      }
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(this.physical_form));

      if (this.physical_form.physical_article != "") {
        url = `${uri}physical/physical_update`;
      } else {
        url = `${uri}physical/physical_add`;
      }
      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          if (res.status === 200) {
            if (res.data != []) {
              toastr(res.msg, "bg-success");
              this.refresh();
            }
          } else {
            toastr(res.msg, "bg-danger");
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición articleSave ${error}`, "bg-danger");
        });
    },
    physical_view: function (physical) {
      this.physical_form = [];
      const url = `${uri}physical/physical_view`;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(physical));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.physical_form = res.data.physical_form;
                this.page_title = "Editar Articulo";
                this.page_form = true;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`Hubo un error en la petición articles_view ${error}`, "bg-danger");
        });
    },
    physical_group: function (physical) {
      this.physical_form = [];
      const url = `${uri}physical/physical_group`;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("form", JSON.stringify(physical));

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.physical_form = res.data.physical_form;
                this.page_title = "Nuevo Articulo";
                this.page_form = true;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`physical_group ${error}`, "bg-danger");
        });
    },
    physical_delete: function (physical) {
      Swal.fire({
        text: `¿Seguro desea eliminar el articulo seleccionado?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${uri}physical/physical_delete`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(physical));

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    this.drafts_list();
                    this.physical_list();
                    toastr(res.msg, "bg-success");
                  }
                  break;
                default:
                  get_action(res.status, res.msg, res.data);
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición articleDelete ${error}`, "bg-danger");
            });
        }
      });
    },
    physical_close: function () {
      this.drafts_list();
      this.physical_list();
      this.physical_form = [];
      this.page_list = true;
    },
    drafts_list: function () {
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(`${uri}physical/physical_drafts`, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.drafts_lists = res.data;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`drafts_list ${error}`, "bg-danger");
        });
    },
    drafts_empty: function () {
      Swal.fire({
        text: `¿Seguro desea borrar todos los borradores?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${uri}physical/physical_empty`;
          let data = new FormData();
          data.append("csrf", csrf);

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              switch (res.status) {
                case 200:
                  if (res.data != []) {
                    toastr(res.msg, "bg-danger");
                    this.drafts_list();
                    this.physical_list();
                  }
                  break;
                default:
                  get_action(res.status, res.msg, res.data);
                  break;
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición articleSave ${error}`, "bg-danger");
            });
        }
      });
    },
    /* MANEJO DE TODO LO RELACIONADO A LOS ARTICULOS */

    /* MANEJO DE TODO LO RELACIONADO A LAS CATEOGORIAS */
    category_add: function () {
      if (!this.category_input) {
        toastr("Debe asignar nombre a la categoria.", "bg-danger");
        return;
      }

      const url = `${uri}category/category_add/physical`;
      let data = new FormData();
      data.append("csrf", csrf);
      data.append("category_name", this.category_input);

      axios
        .post(url, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.category_input = "";
                toastr(res.msg, "bg-success");
                this.category_list();
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
        })
        .catch(function (error) {
          toastr(`category_add ${error}`, "bg-danger");
        });
    },
    category_delete: function () {
      Swal.fire({
        text: `¿Seguro desea borrar la categoria?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = `${uri}category/category_delete`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("category_id", this.category_selected);

          axios
            .post(url, data)
            .then((response) => {
              let res = response.data;
              if (res.status === 200) {
                if (res.data != []) {
                  this.category_list();
                  toastr(res.msg, "bg-success");
                }
              } else {
                toastr(res.msg, "bg-danger");
              }
            })
            .catch(function (error) {
              toastr(`Hubo un error en la petición addCategoria ${error}`, "bg-danger");
            });
        }
      });
    },
    category_list: function () {
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(`${uri}category/category_list`, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.category_selected = res.data.category_selected;
                this.category_listed = res.data.category_listed;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`drafts_list ${error}`, "bg-danger");
        });
    },
    /* MANEJO DE TODO LO RELACIONADO A LAS MARCAS */
    brand_list: function () {
      let data = new FormData();
      data.append("csrf", csrf);

      axios
        .post(`${uri}_brand/brand_list`, data)
        .then((response) => {
          let res = response.data;
          switch (res.status) {
            case 200:
              if (res.data != []) {
                this.brand_listed = res.data.brand_listed;
                //this.brand_selected = res.data.brand_selected;
              }
              break;
            default:
              get_action(res.status, res.msg, res.data);
              break;
          }
          this.page_list = true;
        })
        .catch(function (error) {
          toastr(`drafts_list ${error}`, "bg-danger");
        });
    },
  },
});
