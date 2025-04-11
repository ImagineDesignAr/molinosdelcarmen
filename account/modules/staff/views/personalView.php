<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid">
      <div data-name="cabecera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page.title}}</h4>
            <div class="page-title-right">
              <div class="input-group">
                <button @click="staff_new()" v-if="page.list" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"><i class="ri-add-line"></i> Nuevo</button>
                <button @click.esc="page_state('list')" v-if="page.form" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
                <button @click="staff_save()" v-if="page.form" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="listado" v-if="page.list" class="row">
        <div class="card h-100">
          <div class="card-header border-bottom-dashed border-bottom p-2">
            <div class="row g-3">
              <div class="input-group">
                <input type="text" v-model="staff_search" id="staff_search" class="form-control form-control-sm search me-1" placeholder="Buscar por razon social, contacto, telefono, email...">
                <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light" @click="staff_search = ''">
                  <i class="ri-delete-back-2-line ri-xl align-middle"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-2">
            <ul class="list-group list-group-flush overflow-auto">
              <li v-if="!staff_leaked" class="list-group-item">
                <h6>No hay registros</h6>
              </li>
              <li v-if="staff_leaked" v-for="(staff, index) in staff_leaked" v-show="(page.current - 1) * page.items <= index  && page.current * page.items > index" class="list-group-item p-1">
                <div v-if="staff.spinner" class="row g-1">
                  <div class="d-flex justify-content-center">
                    <div class="loader"></div>
                  </div>
                </div>
                <div v-if="!staff.spinner" class="row g-1">
                  <div class="col-md">
                    <div class="d-flex align-items-center">
                      <img :src="staff.person_picture" alt="" class="rounded avatar-xs me-2">
                      <div>
                        <p class="text-reset fs-14 mb-0 text-capitalize">{{staff.person_name}} {{staff.person_lastname}}</p>
                        <p class="text-muted mb-0">Dni: <span class="fw-medium">{{staff.person_document}}</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div v-for="(profile, index) in staff_profile" class="form-check form-switch form-check-inline">
                      <input
                        v-model="staff.profiles_enabled"
                        :value="profile.person_profile_id"
                        type="checkbox"
                        class="form-check-input"
                        :id="profile.person_profile_name + staff.person_id">
                      <label class="form-check-label" :for="profile.person_profile_name + staff.person_id">
                        {{ profile.person_profile_text }}
                      </label>
                    </div>
                  </div>
                  <div class="col-auto ms-auto">
                    <button @click="access_save(staff)" class="btn btn-icon text-dark">
                      <i class="ri-save-line ri-xl"></i>
                    </button>
                    <button @click="staff_view(staff)" class="btn btn-icon text-primary">
                      <i class="ri-edit-2-line ri-xl"></i>
                    </button>
                    <button @click="staff_delete(staff)" class="btn btn-icon text-danger">
                      <i class="ri-delete-bin-line ri-xl"></i>
                    </button>
                    <button @click="staff_status(staff)" :class="staff.person_condition_color" class="btn btn-icon">
                      <i class="ri-user-3-line ri-xl"></i>
                    </button>
                    <button @click="staff_pass(staff)" class="btn btn-icon text-warning">
                      <i class="ri-key-2-line ri-xl"></i>
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="staff_leaked" class="card-footer">
            <div class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page.current}}/<span class="fw-semibold">{{Math.ceil(staff_leaked.length / page.items)}}</span></div>
              </div>
              <div class="col-sm">
                <nav aria-label="...">
                  <ul class="pagination justify-content-center">
                    <li class="page-item">
                      <span class="page-link cursor-pointer" @click.prevent="page.current = 1"><i class="ri-home-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <span class="page-link cursor-pointer" v-show="page.current != 1" @click.prevent="page.current -= 1"><i class="ri-arrow-left-s-line ri-md align-middle"></i></span>
                    </li>
                    <li class="page-item">
                      <a class="page-link cursor-pointer" v-show="page.current * page.items / staff_leaked.length < 1" @click.prevent="page.current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
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
      <div data-name="formulario" v-if="page.form" class="row">
        <div class="col-xl-3">
          <div class="card h-100">
            <div class="card-body p-4">
              <div class="text-center">
                <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                  <img :src="staff_form.person_picture" class="rounded avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                  <div class="avatar-xs p-0 rounded profile-photo-edit">
                    <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="main_picture">
                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                      <span class="avatar-title rounded bg-light text-body">
                        <i class="ri-camera-fill"></i>
                      </span>
                    </label>
                  </div>
                </div>
                <h5 class="fs-16 mb-1 text-capitalize">{{staff_form.person_name}} {{staff_form.person_lastname}}</h5>
                <p class="text-muted mb-0">{{staff_form.edad}} años</p>
                <p class="text-muted mb-0 text-start"><strong>Dni:</strong> {{staff_form.person_document}} </p>
                <p class="text-muted mb-0 text-start"><strong>Correo Electronico:</strong> {{staff_form.person_email}} </p>
                <p class="text-muted mb-0 text-start"><strong>Observacion:</strong> {{staff_form.professional_observation}} </p>
                <hr />
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-9">
          <div class="card h-100">
            <div class="card-header">
              <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" data-bs-toggle="tab" href="#data" role="tab" aria-selected="true">
                    Datos Personales
                  </a>
                </li>
              </ul>
            </div>
            <div class="card-body p-4">
              <div class="tab-content">
                <div title="data_person" class="tab-pane active" id="data" role="tabpanel">
                  <div class="row mb-1">
                    <div class="col-md-4">
                      <label class="form-label" for="person_document"><mark>Dni</mark></label>
                      <input v-model="staff_form.person_document" id="person_document" type="text" maxlength="8" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label" for="person_cellphone"><mark>Celular</mark></label>
                      <input v-model="staff_form.person_cellphone" id="person_cellphone" type="text" class="form-control text-capitalize" paceholder="Incluya codigo de area">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label" for="person_email">Correo Electronico</label>
                      <input v-model="staff_form.person_email" id="person_email" type="email" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-md-4">
                      <label class="form-label" for="person_name"><mark>Nombres</mark></label>
                      <input v-model="staff_form.person_name" id="person_name" type="text" class="form-control text-capitalize" required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label" for="person_lastname">Apellidos</label>
                      <input v-model="staff_form.person_lastname" id="person_lastname" type="text" class="form-control text-capitalize" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label" for="person_birthday">Fecha Nacimiento</label>
                      <input v-model="staff_form.person_birthday" @input="edad()" id="person_birthday" type="date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label" for="person_gender">Sexo</label>
                      <select v-model="staff_form.person_gender" id="person_gender" class="form-control">
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                        <option value="3">No especifica</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-md-6">
                      <label class="form-label" for="person_address">Direccion Completa</label>
                      <input v-model="staff_form.person_address" id="person_address" type="text" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label" for="person_city">Ciudad</label>
                      <input v-model="staff_form.person_city" id="person_city" type="text" class="form-control text-capitalize" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label" for="person_postalcode">Codigo Postal</label>
                      <input v-model="staff_form.person_postalcode" id="person_postalcode" type="text" class="form-control text-capitalize" required>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label" for="person_employee">Area</label>
                      <select v-model="staff_form.person_employee" class="form-control" id="person_employee">
                        <option value="1">No Aplica</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label class="form-label" for="person_observation">Observaciones</label>
                      <textarea v-model="staff_form.person_observation" id="person_observation" class="form-control" style="height:100px"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-name="error" v-if="page.error" class="row justify-content-between align-items-center text-center">
        <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
        <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
      </div>
      <div data-name="spinner" v-if="page.spinner" class="row justify-content-center">
        <div class="loader"></div>
      </div>
    </div>
    <footer class="footer"></footer>
  </div>
</div>

<script>
  var vm = new Vue({
    el: "#app",
    data: function() {
      return {
        page: {
          list: true,
          form: false,
          error: false,
          spinner: false,
          title: "Administracion de Personal",
          current: 1,
          items: 10,
        },
        staff_search: "",
        staff_profile: {},
        staff_listed: {},
        staff_leaked: {},
        staff_form: {},
      };
    },
    mounted: function() {
      this.staff_list();
    },
    watch: {
      staff_search: function() {
        this.staff_filter();
      },
    },
    computed: {},
    methods: {
      page_state(state) {
        this.page.list = state === "list";
        this.page.form = state === "form";
        this.page.error = state === "error";
        this.page.spinner = state === "spinner";
      },
      color_status(status) {
        return status !== 0 ? "text-success" : "text-danger";
      },
      async picture_upload() {
        const url = get_meta("urlupload");
        const file_img = this.$refs.main_picture;

        // Validar que se haya seleccionado un archivo
        if (!file_img || !file_img.files || file_img.files.length === 0) {
          toastr("No se ha seleccionado ningún archivo.", "bg-warning");
          return;
        }

        const file = file_img.files[0];
        const data = new FormData();
        data.append("csrf", csrf);
        data.append("filename", file);

        try {
          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.staff_form.person_picture = res.data;
            toastr(res.msg, "bg-success");
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(`picture_upload: ${error}`, "bg-danger");
        }
      },
      async staff_list() {
        this.page_state('spinner');
        try {
          const url = `${uri}staff/staff_store`;
          let data = new FormData();
          data.append("csrf", csrf);

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.staff_profile = res.data.staff_profile;
            this.staff_listed = res.data.staff_listed;
            this.staff_filter();
            this.page_state('list');
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async staff_new() {
        this.page_state('spinner');
        try {
          const url = `${uri}staff/staff_new`;
          let data = new FormData();
          data.append("csrf", csrf);

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.staff_form = res.data.staff_form;
            this.staff_form.edad = calculate_age(this.staff_form.person_birthday);
            this.page_state('form');
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async staff_view(staff) {
        try {
          const url = `${uri}staff/staff_view`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.staff_form = res.data.staff_form;
            this.staff_form.edad = calculate_age(this.staff_form.person_birthday);
            this.page_state('form');
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async staff_delete(staff) {
        try {
          const result = await Swal.fire({
            text: "¿Seguro desea eliminar el personal seleccionado?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
          });

          if (!result.isConfirmed) {
            return;
          }

          staff.spinner = true;
          const url = `${uri}staff/staff_delete`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.staff_list();
            toastr(res.msg, "bg-success");
          } else {
            get_action(res.status, res.msg);
          }

        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async staff_save() {
        this.btn_save = false;
        var url;

        try {
          this.validate_save();
          if (this.staff_form.person_id != "") {
            url = `${uri}staff/staff_update`;
          } else {
            url = `${uri}staff/staff_add`;
          }

          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(this.staff_form));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            toastr(res.msg, "bg-success");
            this.staff_list();
          } else if (res.status === 400) {
            toastr(res.msg, "bg-danger");
            this.page_state('form');
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      validate_save() {
        const {
          person_document,
          person_name,
          person_cellphone
        } = this.staff_form;

        if (!person_name.trim()) throw new Error("Debe completar campo Nombre.");
        if (String(person_document).trim().length < 6) throw new Error("DNI invalido.");
        if (String(person_cellphone).trim().length < 10) throw new Error("Celular invalido.");
      },
      staff_filter() {
        let text = this.staff_search.trim().toLowerCase();
        // if (text.length <= 1) return (this.staff_leaked = false);
        let array_list = this.staff_listed.filter((item) => item.keywords.includes(text));
        // Actualiza la variable filtrada o asigna false si no hay coincidencias
        this.staff_leaked = array_list.length ? array_list : false;
        this.page_state("list");
      },
      async staff_status(staff) {
        staff.spinner = true;
        try {
          const url = `${uri}staff/staff_status`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            Object.assign(staff, res.data.staff_form);
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async staff_pass(staff) {
        try {
          const result = await Swal.fire({
            text: "¿Seguro desea resetear contraseña de personal seleccionado?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, resetear",
            cancelButtonText: "Cancelar",
          });

          if (!result.isConfirmed) {
            return;
          }

          staff.spinner = true;
          const url = `${uri}staff/staff_pass`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            toastr(res.msg, "bg-success");
            staff.spinner = false;
          } else {
            get_action(res.status, res.msg);
          }

        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      async access_save(staff) {
        staff.spinner = true;
        try {
          const url = `${uri}staff/access_save`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(staff));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            toastr(res.msg, "bg-success");
            staff.spinner = false;
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
    },
  });
</script>

<!-- Version 5.0 - async/await -->