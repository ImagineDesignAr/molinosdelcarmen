<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid mb-2">
      <div data-name="cabecera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page_title}}</h4>
            <div class="page-title-right">
              <div class="input-group">
                <button v-if="page_form" type="button" class="btn btn-primary btn-sm" @click="save_store()">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="formulario" v-if="page_form" class="row">
        <div class="row justify-content-center">
          <div class="col-xl-8 ">
            <div class="card h-100">
              <div class="card-body p-4">
                <div class="row">
                  <h5 class="fw-bold">Datos Sucursal</h5>
                </div>
                <div class="row">
                  <div class="col-md-auto">
                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                      <img :src="store_form.store_picture" class="rounded avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                      <div class="avatar-xs p-0 rounded profile-photo-edit">
                        <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="main_picture">
                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                          <span class="avatar-title rounded bg-light text-body">
                            <i class="ri-camera-fill"></i>
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="row">
                      <div class="col-md">
                        <label class="form-label" for="store_name">Nombre</label>
                        <input v-model="store_form.store_name" id="store_name" type="text" class="form-control text-capitalize" required>
                      </div>
                      <div class="col-md">
                        <label class="form-label" for="store_shortname">Nombre Corto</label>
                        <input v-model="store_form.store_shortname" id="store_shortname" type="text" class="form-control" required>
                      </div>
                      <div class="col-md">
                        <label class="form-label" for="store_type">Tipo Sucursal</label>
                        <select v-model="store_form.store_type" id="store_type" class="form-control">
                          <option value="office">Oficina</option>
                          <option value="store">Venta al Publico</option>
                          <option value="deposit">Deposito</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <label class="form-label" for="store_address">Direccion</label>
                    <input v-model="store_form.store_address" id="store_address" type="text" class="form-control text-capitalize" paceholder="Direccion completa">
                  </div>
                  <div class="col-md">
                    <label class="form-label" for="store_description">Descripcion</label>
                    <input v-model="store_form.store_description" id="store_description" type="text" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <label class="form-label" for="store_phonenumber">Telefono</label>
                    <input v-model="store_form.store_phonenumber" id="store_phonenumber" type="tel" class="form-control">
                  </div>
                  <div class="col-md">
                    <label class="form-label" for="store_instagram">Instagram</label>
                    <input v-model="store_form.store_instagram" id="store_instagram" type="tel" class="form-control">
                  </div>
                  <div class="col-md">
                    <label class="form-label" for="store_facebook">Facebook</label>
                    <input v-model="store_form.store_facebook" id="store_facebook" type="tel" class="form-control">
                  </div>
                  <div class="col-md">
                    <label class="form-label" for="store_email">Correo Electronico</label>
                    <input v-model="store_form.store_email" id="store_email" type="tel" class="form-control">
                  </div>
                  <div class="col-md">
                    <label class="form-label" for="store_web">Link Web</label>
                    <input v-model="store_form.store_web" id="store_web" type="tel" class="form-control">
                  </div>
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
    data: function() {
      return {
        page_form: true,
        page_error: false,
        page_spinner: false,

        page_title: "Nueva Sucursal",
        page_options: {},
        store_form: {},
      };
    },
    mounted: function() {
      this.store_form = {
        store_picture: urlimages + "_local.png",
        store_type: "store"
      };
    },
    watch: {
      page_form: function() {
        if (this.page_form) {
          this.page_spinner = false;
          this.page_error = false;
        }
      },
      page_error: function() {
        if (this.page_error) {
          this.page_form = false;
          this.page_spinner = false;
        }
      },
      page_spinner: function() {
        if (this.page_spinner) {
          this.page_form = false;
          this.page_error = false;
        }
      },
    },
    computed: {},
    methods: {
      picture_upload: function() {
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
          .catch(function(error) {
            toastr(`Hubo un error en la petición main_picture_upload ${error}`, "bg-danger");
          });
      },
      save_store: function() {
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
                  this.store_form = {
                    store_picture: urlimages + "_local.png",
                    store_type: "store"
                  };
                  this.page_form = true;
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_error = true;
                break;
            }
          })
          .catch(function(error) {
            toastr(`save_store ${error}`, "bg-danger");
          });
      },
    },
  });
</script>