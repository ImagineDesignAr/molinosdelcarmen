<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid">
      <div data-name="cabezera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page_title}}</h4>
            <div class="page-title-right">
              <button @click="blog_new()" v-if="page_list" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"><i class="ri-add-line"></i> Nuevo</button>
              <button @click.esc="blog_close();" v-if="page_form" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
              <button @click="blog_save()" v-if="page_form" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
            </div>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="listado" class="row" v-if="page_list">
        <div class="card">
          <div class="card-body border-bottom-dashed border-bottom">
            <div class="row g-3">
              <div class="input-group">
                <input type="text" v-model="blog_search" id="blog_search" class="form-control form-control-sm search me-1" placeholder="">
                <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light" @click="blog_search = ''">
                  <i class="ri-delete-back-2-line ri-xl align-middle"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-2">
            <ul class="list-group list-group-flush overflow-auto">
              <li v-if="!blog_leaked" class="list-group-item">
                <h6>No hay registros</h6>
              </li>
              <li v-if="blog_leaked" v-for="(blog, index) in blog_leaked" v-show="(page_current - 1) * page_items <= index  && page_current * page_items > index" class="list-group-item">
                <div class="row g-1">
                  <div class="col-md-10">
                    <div class="d-flex align-items-center">
                      <img :src="blog.blog_picture" alt="" class="img-thumbnail me-2" width="50">
                      <div>
                        <p class="text-reset fs-14 mb-0 text-capitalize">{{blog.blog_title}}</p>
                        <p class="text-muted mb-0">Fecha: <span class="fw-medium">{{blog.blog_date}}</span></p>
                        <p class="text-muted mb-0">Fuente: <a :href="blog.blog_link" target="_blank" class="fs-13 fw-medium">{{blog.blog_link}}<i class="ri-arrow-right-s-line align-bottom"></i></a></p>
                        <p class="text-muted mb-0">Resumen: <span class="fw-medium text-secondary">{{blog.blog_summary}}</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-auto ms-auto">
                    <div class="d-flex align-items-center justify-content-end">
                      <button class="btn btn-icon text-primary" @click="blog_view(blog)">
                        <i class="ri-edit-2-line ri-xl"></i>
                      </button>
                      <button class="btn btn-icon text-danger" @click="blog_delete(blog)">
                        <i class="ri-delete-bin-line ri-xl"></i>
                      </button>
                      <div class="form-check form-switch form-switch-success" :id="blog.blog_id">
                        <input v-model="blog.blog_condition" @click="blog_status(blog)" class="form-check-input" type="checkbox" role="switch" :name="blog.blog_condition" checked>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="blog_leaked" class="card-footer">
            <div class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page_current}}/<span class="fw-semibold">{{Math.ceil(blog_leaked.length / page_items)}}</span></div>
              </div>
              <div class="col-sm">
                <nav aria-label="...">
                  <ul class="pagination justify-content-center">
                    <li class="page-item">
                      <span class="page-link cursor-pointer" @click.prevent="page_current = 1"><i class="ri-home-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <span class="page-link cursor-pointer" v-show="page_current != 1" @click.prevent="page_current -= 1"><i class="ri-arrow-left-s-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <a class="page-link cursor-pointer" v-show="page_current * page_items / blog_leaked.length < 1" @click.prevent="page_current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
                    </li>
                  </ul>
                </nav>
              </div>
              <div class="col-sm">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-name="formulario" class="row" v-if="page_form">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body p-4">
              <div class="text-center">
                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                  <img :src="blog_form.blog_picture" class="img-fluid" alt="imagen de la noticia">
                  <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                    <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="profile_picture">
                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                      <span class="avatar-title rounded-circle bg-light text-body">
                        <i class="ri-camera-fill"></i>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8">
          <div class="card h-100">
            <div class="card-body p-4">
              <div class="row">
                <div class="col-md-3">
                  <label class="form-label">Fecha</label>
                  <input type="date" class="form-control" v-model="blog_form.blog_date" required>
                </div>
                <div class="col-md-9">
                  <label class="form-label">Titulo</label>
                  <input type="text" class="form-control" v-model="blog_form.blog_title">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label">Resumen</label>
                  <textarea class="form-control" maxlength="150" v-model="blog_form.blog_summary" style="height:100px"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label">Contenido</label>
                  <textarea class="form-control" v-model="blog_form.blog_content" style="height:100px"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label">Fuente</label>
                  <input type="text" class="form-control" v-model="blog_form.blog_link">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-name="error" v-if="page_error" class="row justify-content-between align-items-center text-center">
        <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
        <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
      </div>
      <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
        <div class="loader"></div>
      </div>
    </div>
  </div>
  <footer class="footer"></footer>
</div>
<script>
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
    mounted: function() {
      this.blog_list();
    },
    watch: {
      page_list: function() {
        if (this.page_list) {
          this.page_spinner = false;
          this.page_error = false;
          this.page_form = false;
        }
      },
      page_form: function() {
        if (this.page_form) {
          this.page_spinner = false;
          this.page_error = false;
          this.page_list = false;
        }
      },
      page_spinner: function() {
        if (this.page_spinner) {
          this.page_error = false;
          this.page_list = false;
          this.page_form = false;
        }
      },
      page_error: function() {
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
      picture_upload: function() {
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
          .catch(function(error) {
            toastr(`Hubo un error en la petición uploadImage ${error}`, "bg-danger");
          });
      },
      blog_new: function() {
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
          .catch(function(error) {
            toastr(`blog_new ${error}`, "bg-danger");
          });
      },
      blog_delete: function(seleccion) {
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
              .catch(function(error) {
                toastr(`blog_delete ${error}`, "bg-danger");
              });
          }
        });
      },
      blog_save: function() {
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
          .catch(function(error) {
            toastr(`blog_save ${error}`, "bg-danger");
          });
      },
      blog_status: function(seleccion) {
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
          .catch(function(error) {
            toastr(`blog_status ${error}`, "bg-danger");
          });
      },
      blog_view: function(seleccion) {
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
          .catch(function(error) {
            toastr(`blog_view ${error}`, "bg-danger");
          });
      },
      blog_list: function() {
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
          .catch(function(error) {
            toastr(`blog_list ${error}`, "bg-danger");
          });
      },
      blog_filter: function() {
        let array_list = this.blog_listed;
        let text = this.blog_search.toLowerCase();
        // Filtros en array
        array_list = array_list.filter((item) => item.keywords.match(text));
        console.log(array_list);
        // Array filtrado
        if (!array_list.length) return (this.blog_leaked = false);
        this.blog_leaked = array_list;
      },
      blog_close: function() {
        this.blog_form = null;
        this.page_list = true;
      },
    },
  });
</script>