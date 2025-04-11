<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid mb-2">
      <div data-name="cabecera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page.title}}</h4>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="listado" v-if="page.list" class="row">
        <div class="card h-100">
          <div class="card-header border-bottom-dashed border-bottom p-2">
            <div class="row g-3">
              <div class="input-group">
                <input v-model="store_search" type="text" id="store_search" class="form-control form-control-sm search me-1" placeholder="Buscar por razon social, contacto, telefono, email...">
                <button @click="store_search = ''" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light">
                  <i class="ri-delete-back-2-line ri-xl align-middle"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-2">
            <ul class="list-group list-group-flush overflow-auto">
              <li v-if="!store_leaked" class="list-group-item">
                <h6>No hay registros</h6>
              </li>
              <li v-if="store_leaked" class="list-group-item p-1" v-for="(store, index) in store_leaked" v-show="(page.current - 1) * page.items <= index  && page.current * page.items > index">
                <div class="row g-1" v-if="store.spinner == true">
                  <div class="d-flex justify-content-center">
                    <div class="loader"></div>
                  </div>
                </div>
                <div class="row g-1" v-if="store.spinner == false">
                  <div class="col-md-5">
                    <div class="d-flex align-items-center">
                      <img :src="store.store_picture" alt="" class="rounded avatar-xs me-2">
                      <div>
                        <p class="text-reset fs-14 mb-0 text-capitalize">{{store.store_name}}</p>
                        <p class="text-muted mb-0">Ubicación: <span class="fw-medium">{{store.store_address}}</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-end ms-auto">
                    <p class="text-muted mb-0">Whatsapp: <span class="fw-medium">{{store.store_phonenumber}}</span></p>
                    <p v-if="store.store_hour_status" class="text-muted mb-0">Estado: <span class="fw-medium text-success">ABIERTO</span></p>
                    <p v-if="!store.store_hour_status" class="text-muted mb-0">Estado: <span class="fw-medium text-danger">CERRADO</span></p>
                  </div>
                  <div class="col-md-3 text-end">
                    <button @click="store_printer(store)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_impresora" class="btn btn-icon text-dark">
                      <i class="ri-printer-line ri-xl"></i>
                    </button>
                    <button @click="hour_list(store)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_horario" class="btn btn-icon text-dark">
                      <i class="ri-time-line ri-xl"></i>
                    </button>
                    <button @click="zone_list(store)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_zonas" class="btn btn-icon text-dark">
                      <i class="ri-map-2-line ri-xl"></i>
                    </button>
                    <button @click="estate_list(store)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_estate" class="btn btn-icon text-dark">
                      <i class="ri-government-line ri-xl"></i>
                    </button>
                    <button @click="store_view(store,index)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edicion" class="btn btn-icon text-primary">
                      <i class="ri-edit-2-line ri-xl"></i>
                    </button>
                    <button @click="store_delete(store)" class="btn btn-icon text-danger">
                      <i class="ri-delete-bin-line ri-xl"></i>
                    </button>
                    <button @click="store_status(store)" :class="color_status(store.store_condition)" class="btn btn-icon">
                      <i class="ri-store-2-line ri-xl"></i>
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="card-footer">
            <div v-if="store_leaked" class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page.current}}/<span class="fw-semibold">{{Math.ceil(store_leaked.length / page.items)}}</span></div>
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
                      <a class="page-link cursor-pointer" v-show="page.current * page.items / store_leaked.length < 1" @click.prevent="page.current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
                    </li>
                  </ul>
                </nav>
              </div>
              <div class="col-sm">
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div id="map" style="height: 600px;"></div>
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
      <div data-name="printer" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_impresora" aria-labelledby="offcanvas_impresora">
        <div class="offcanvas-header border pt-2 pb-1">
          <h5>{{store_form.store_name}} <span>ID: {{store_form.store_id}}</span></h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
          <div class="card border border-dark mb-2">
            <div class="card-body p-1">
              <div class="col-sm">
                <label class="form-label" for="store_printer_name">Nombre Impresora</label>
                <input v-model="store_form.store_printer_name" id="store_printer_name" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_initial_height">Margen Arriba</label>
                <input v-model="store_form.store_printer_initial_height" id="store_printer_initial_height" type="number" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_left_margin">Margen Izquierdo</label>
                <input v-model="store_form.store_printer_left_margin" id="store_printer_left_margin" type="number" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_leading">Tamaño Linea</label>
                <input v-model="store_form.store_printer_leading" id="store_printer_leading" type="number" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_width">Tamaño Ticket</label>
                <input v-model="store_form.store_printer_width" id="store_printer_width" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_font">Fuente</label>
                <input v-model="store_form.store_printer_font" id="store_printer_font" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_printer_condition">Condicion</label>
                <select v-model="store_form.store_printer_condition" id="store_printer_condition" class="form-control">
                  <option value="0">INACTIVO</option>
                  <option value="1">ACTIVO</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas-footer border-top p-3 text-end">
          <button data-bs-dismiss="offcanvas" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
          <button @click="store_update" data-bs-dismiss="offcanvas" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
        </div>
      </div>
      <div data-name="sucursal" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_edicion" aria-labelledby="offcanvas_edicion">
        <div class="offcanvas-header border pt-2 pb-1">
          <h5>{{store_form.store_name}} <span>ID: {{store_form.store_id}}</span></h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
          <div class="card border border-dark mb-2">
            <div class="card-body p-1">
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
                <div class="col-sm">
                  <label class="form-label" for="store_name">Titulo sucursal</label>
                  <input v-model="store_form.store_name" id="store_name" type="text" class="form-control">
                </div>
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_shortname">Nombre Corto</label>
                <input v-model="store_form.store_shortname" id="store_shortname" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_address">Direccion completa</label>
                <textarea v-model="store_form.store_address" id="store_address" class="form-control"></textarea>
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_phonenumber">Whatsapp</label>
                <input v-model="store_form.store_phonenumber" id="store_phonenumber" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_facebook">Facebook</label>
                <input v-model="store_form.store_facebook" id="store_facebook" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_instagram">Instagram</label>
                <input v-model="store_form.store_instagram" id="store_instagram" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_email">Correo Electronico</label>
                <input v-model="store_form.store_email" id="store_email" type="text" class="form-control">
              </div>
              <div class="col-sm">
                <label class="form-label" for="store_web">Link Web</label>
                <input v-model="store_form.store_web" id="store_web" type="text" class="form-control">
              </div>
              <div class="col-md">
                <label class="form-label" for="store_lat">Latitud (decimales)</label>
                <input v-model="store_form.store_lat" id="store_lat" type="text" class="form-control">
              </div>
              <div class="col-md">
                <label class="form-label" for="store_lng">Longitud (decimales)</label>
                <input v-model="store_form.store_lng" id="store_lng" type="text" class="form-control">
              </div>
              <div class="col-md">
                <label class="form-label" for="store_color">Color</label>
                <input v-model="store_form.store_color" id="store_color" type="color" class="form-control">
              </div>
              <div class="col-md">
                <label class="form-label" for="store_radius">Radio Cobertura (mts)</label>
                <input v-model="store_form.store_radius" id="store_radius" type="text" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas-footer border-top p-3 text-end">
          <button data-bs-dismiss="offcanvas" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
          <button @click="store_update" data-bs-dismiss="offcanvas" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
        </div>
      </div>
      <div data-name="horario" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_horario" aria-labelledby="offcanvas_horario">
        <div class="offcanvas-header border pt-2 pb-1">
          <h5>{{store_form.store_name}}</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
          <div class="card border border-dark mb-2">
            <div class="card-body p-1">
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_hour_status">Estado</label>
                  <select v-model="store_form.store_hour_status" id="store_hour_status" class="form-control">
                    <option value="0">CERRADO</option>
                    <option value="1">ABIERTO</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_hour_open_am">Apertura AM</label>
                  <input v-model="store_form.store_hour_open_am" id="store_hour_open_am" type="time" class="form-control">
                </div>
                <div class="col-sm">
                  <label class="form-label" for="store_hour_close_am">Cierre AM</label>
                  <input v-model="store_form.store_hour_close_am" id="store_hour_close_am" type="time" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_hour_open_pm">Apertura PM</label>
                  <input v-model="store_form.store_hour_open_pm" id="store_hour_open_pm" type="time" class="form-control">
                </div>
                <div class="col-sm">
                  <label class="form-label" for="store_hour_close_pm">Cierre PM</label>
                  <input v-model="store_form.store_hour_close_pm" id="store_hour_close_pm" type="time" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas-footer border-top p-3 text-end">
          <button data-bs-dismiss="offcanvas" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
          <button @click="hour_update" data-bs-dismiss="offcanvas" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
        </div>
      </div>
      <div data-name="zonas" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_zonas" aria-labelledby="offcanvas_zonas">
        <div class="offcanvas-header border pt-2 pb-1">
          <h5>{{store_form.store_name}}</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
          <div class="card border border-dark mb-2">
            <div class="card-body p-1">
              <table class="table table-nowrap">
                <thead>
                  <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Orden</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(zone,index) in store_zone">
                    <td>
                      <input v-model="zone.store_zone_name" id="store_zone_name" type="text" class="form-control form-control-sm">
                    </td>
                    <td>
                      <input v-model="zone.store_zone_cost" id="store_zone_cost" type="number" class="form-control form-control-sm">
                    </td>
                    <td>
                      <input v-model="zone.store_zone_orderby" id="store_zone_orderby" type="number" class="form-control form-control-sm">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="offcanvas-footer border-top p-3 text-end">
          <button data-bs-dismiss="offcanvas" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
          <button @click="zone_update" data-bs-dismiss="offcanvas" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
        </div>
      </div>
      <div data-name="afip" class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvas_estate" aria-labelledby="offcanvas_estate">
        <div class="offcanvas-header border pt-2 pb-1">
          <h5>{{store_form.store_name}}</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
          <div class="card border border-dark mb-2">
            <div class="card-body p-1">
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_cuit">CUIT</label>
                  <input v-model="store_form.store_estate_cuit" id="store_estate_cuit" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_business">Razon Social</label>
                  <input v-model="store_form.store_estate_business" id="store_estate_business" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_start">Inicio Actividad</label>
                  <input v-model="store_form.store_estate_start" id="store_estate_start" type="date" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_income">IIBB</label>
                  <input v-model="store_form.store_estate_income" id="store_estate_income" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_vat">Condicion IVA</label>
                  <select v-model="store_form.store_estate_vat" id="store_estate_vat" class="form-control">
                    <option value="monotributo">Monotributo</option>
                    <option value="responsable_incripto">Responsable Inscripto</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_point">Punto de Venta</label>
                  <input v-model="store_form.store_estate_point" id="store_estate_point" type="number" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_invoice">Comprobante Predeterminado</label>
                  <select v-model="store_form.store_estate_invoice" id="store_estate_invoice" class="form-control">
                    <option value="1">Factura A</option>
                    <option value="6">Factura B</option>
                    <option value="11">Factura C</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_crt">CRT</label>
                  <input v-model="store_form.store_estate_crt" id="store_estate_crt" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_key">KEY</label>
                  <input v-model="store_form.store_estate_key" id="store_estate_key" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_folder">FOLDER</label>
                  <input v-model="store_form.store_estate_folder" id="store_estate_folder" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_expiration">Vencimiento</label>
                  <input v-model="store_form.store_estate_expiration" id="store_estate_expiration" type="date" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-sm">
                  <label class="form-label" for="store_estate_production">Produccion</label>
                  <select v-model="store_form.store_estate_production" id="store_estate_production" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas-footer border-top p-3 text-end">
          <button data-bs-dismiss="offcanvas" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
          <button @click="estate_update" data-bs-dismiss="offcanvas" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer">
  </footer>
</div>

<script>
  var vm = new Vue({
    el: "#app",
    data: function() {
      return {
        page: {
          list: false,
          form: true,
          error: false,
          spinner: true,
          title: "Administracion de Sucursales",
          current: 1,
          items: 10,
        },
        store_search: "",
        store_listed: {},
        store_leaked: {},
        store_form: {},
        store_zone: {},

        store_index: -1,
        store_edition: false,

        locations: [],
      };
    },
    mounted: function() {
      this.store_list();
    },
    watch: {
      store_search: function() {
        this.store_filter();
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
      color_status: function(status) {
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
            this.store_form.store_picture = res.data;
            toastr(res.msg, "bg-success");
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(`picture_upload: ${error}`, "bg-danger");
        }
      },
      async store_list() {
        try {
          const url = `${uri}stores/store_list`;
          let data = new FormData();
          data.append("csrf", csrf);

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.store_listed = res.data.store_listed;
            this.store_filter();
            this.store_maps();
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      store_update: function() {
        this.store_form.spinner = true;
        if (!this.store_form.store_name) {
          toastr("Debe completar Nombre.", "bg-danger");
          return;
        }
        if (!this.store_form.store_address) {
          toastr("Debe completar campo Direccion.", "bg-danger");
          return;
        }
        url = `${uri}stores/store_update`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(this.store_form));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data == []) break;
                toastr(res.msg, "bg-success");
                this.store_form.spinner = false;
                this.page_state('list');
                this.store_maps();
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },
      store_status: function(store) {
        store.spinner = true;
        url = `${uri}stores/store_status`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(store));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != []) {
                  toastr(res.msg, "bg-success");
                  store.store_condition = res.data.store_condition;
                  store.spinner = false;
                  this.page_state('list');
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },
      store_delete: function(store) {
        Swal.fire({
          text: `¿Seguro desea eliminar la sucursal seleccionada?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            store.spinner = true;
            const url = `${uri}stores/store_delete`;
            let data = new FormData();
            data.append("csrf", csrf);
            data.append("form", JSON.stringify(store));

            axios
              .post(url, data)
              .then((response) => {
                let res = response.data;
                switch (res.status) {
                  case 200:
                    if (res.data != []) {
                      this.store_listed = res.data.store_listed;
                      this.store_filter();
                      this.page_state('list');
                    }
                    break;
                  default:
                    get_action(res.status, res.msg);
                    this.page_state('error');
                    break;
                }
              })
              .catch(function(error) {
                toastr(`Hubo un error en la petición articleDelete ${error}`, "bg-danger");
              });
          }
        });
      },
      store_view: function(store, index) {
        this.store_edition = true;
        this.store_form = store;
        this.store_index = index;
      },

      hour_list: function(store) {
        url = `${uri}stores/hour_list`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(store));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != []) {
                  this.store_form = res.data.store_time;
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },
      hour_update: function() {
        this.store_form.spinner = true;
        url = `${uri}stores/hour_update`;
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
                  this.store_list();
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },

      zone_list: function(store) {
        url = `${uri}stores/zone_list`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(store));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != []) {
                  this.store_zone = res.data.store_zone;
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },
      zone_update: function() {
        this.store_form.spinner = true;
        url = `${uri}stores/zone_update`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(this.store_zone));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != []) {
                  toastr(res.msg, "bg-success");
                  this.store_form.spinner = false;
                  this.page_state('list');
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },

      estate_list: function(store) {
        url = `${uri}stores/estate_list`;
        let data = new FormData();
        data.append("csrf", csrf);
        data.append("form", JSON.stringify(store));

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                if (res.data != []) {
                  this.store_form = res.data.store_estate;
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`Hubo un error en la petición save_store ${error}`, "bg-danger");
          });
      },
      estate_update: function() {
        this.store_form.spinner = true;
        url = `${uri}stores/estate_update`;
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
                  this.store_form.spinner = false;
                  this.page_state('list');
                }
                break;
              default:
                get_action(res.status, res.msg);
                this.page_state('error');
                break;
            }
          })
          .catch(function(error) {
            toastr(`estate_update ${error}`, "bg-danger");
          });
      },

      store_filter: function() {
        let array_list = this.store_listed;
        let text = this.store_search.toLowerCase();
        // Filtros en array
        array_list = array_list.filter((item) => item.keywords.match(text));
        // Array filtrado
        if (!array_list.length) return (this.store_leaked = false);
        this.store_leaked = array_list;
        this.page_state('list');
      },
      store_maps: function() {
        if (this.store_leaked.length === 0) return;

        // Limpiar locations antes de agregar nuevos datos
        this.locations = [];

        // Recorrer las sucursales y agregarlas al array
        this.store_leaked.forEach((store) => {
          this.locations.push({
            lat: store.store_lat,
            lng: store.store_lng,
            color: store.store_color || "blue",
            iconUrl: store.store_icon || "https://example.com/default-icon.png",
            name: store.store_name,
            address: store.store_address,
            radius: store.store_radius,
          });
        });

        // Asegurar que el DOM está listo
        this.$nextTick(() => {
          this.initMap();
        });
      },

      async store_printer(store) {
        try {
          const url = `${uri}stores/printer_store`;
          let data = new FormData();
          data.append("csrf", csrf);
          data.append("form", JSON.stringify(store));

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.store_form = res.data.store_printer;
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
      },
      initMap: function() {
        if (this.locations.length === 0) return;

        // Verificar si ya hay un mapa para evitar errores
        if (this.map) {
          this.map.remove();
        }

        // Inicializar el mapa centrado en la primera ubicación
        this.map = L.map("map").setView([this.locations[0].lat, this.locations[0].lng], 13);

        // Agregar la capa base de OpenStreetMap
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "© OpenStreetMap contributors",
        }).addTo(this.map);

        // Agregar círculos y marcadores
        this.locations.forEach((location) => {
          L.circle([location.lat, location.lng], {
            color: location.color,
            fillColor: location.color,
            fillOpacity: 0.2,
            radius: location.radius,
          }).addTo(this.map);

          // Icono personalizado
          const customIcon = L.icon({
            iconUrl: location.iconUrl,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
          });

          // Marcador con popup
          L.marker([location.lat, location.lng], {
            icon: customIcon
          }).addTo(this.map).bindPopup(`<b>${location.name}</b><br>${location.address}`);
        });
      },

    },
  });
</script>