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
                <input v-model="printer_search" type="text" id="printer_search" class="form-control form-control-sm search me-1" placeholder="Buscar por sucursals">
                <button @click="printer_search = ''" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light">
                  <i class="ri-delete-back-2-line ri-xl align-middle"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-2">
            <ul class="list-group list-group-flush overflow-auto">
              <li v-if="!printer_leaked" class="list-group-item">
                <h6>No hay registros</h6>
              </li>
              <li v-if="printer_leaked" class="list-group-item p-1" v-for="(printer, index) in printer_leaked" v-show="(page.current - 1) * page.items <= index  && page.current * page.items > index">
                <div class="row g-1">
                  <div class="col-md-4">
                    <p class="text-reset fs-14 mb-0 text-capitalize">{{printer.store_name}}</p>
                  </div>
                  <div class="col-md-4">
                    <p class="text-muted mb-0"><span class="fw-medium">{{printer.store_type}}</span></p>
                  </div>
                  <div class="col-auto ms-auto">
                    <button @click="printer_view(printer,index)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edicion" class="btn btn-icon text-primary">
                      <i class="ri-edit-2-line ri-xl"></i>
                    </button>
                    <button @click="printer_delete(printer)" class="btn btn-icon text-danger">
                      <i class="ri-delete-bin-line ri-xl"></i>
                    </button>
                    <button @click="printer_status(printer)" :class="color_status(printer.store_printer_condition)" class="btn btn-icon">
                      <i class="ri-store-2-line ri-xl"></i>
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="card-footer">
            <div v-if="printer_leaked" class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page.current}}/<span class="fw-semibold">{{Math.ceil(printer_leaked.length / page.items)}}</span></div>
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
                      <a class="page-link cursor-pointer" v-show="page.current * page.items / printer_leaked.length < 1" @click.prevent="page.current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
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
      <div data-name="error" v-if="page.error" class="row justify-content-between align-items-center text-center">
        <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
        <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
      </div>
      <div data-name="spinner" v-if="page.spinner" class="row justify-content-center">
        <div class="loader"></div>
      </div>
      <!-- 
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
      -->
    </div>
  </div>
</div>

<script>
  var vm = new Vue({
    el: "#app",
    data: function() {
      return {
        page: {
          list: false,
          error: false,
          spinner: true,
          title: "Administracion Impresoras",
          current: 1,
          items: 10,
        },

        printer_search: "",
        printer_listed: {},
        printer_leaked: {},
        printer_form: {},
      };
    },
    mounted: function() {
      this.stores_printer();
    },
    watch: {
      printer_search: function() {
        this.printer_filter();
      },
    },
    computed: {},
    methods: {
      page_state(state) {
        this.page.list = state === "list";
        this.page.error = state === "error";
        this.page.spinner = state === "spinner";
      },
      color_status(status) {
        return status !== 0 ? "text-success" : "text-danger";
      },
      async stores_printer() {
        this.page_state('spinner');
        try {
          const url = `${uri}spooling/printer_list`;
          let data = new FormData();
          data.append("csrf", csrf);

          const response = await axios.post(url, data);
          const res = response.data;

          if (res.status === 200) {
            this.printer_listed = res.data.printer_listed;
            this.printer_filter();
          } else {
            get_action(res.status, res.msg);
          }
        } catch (error) {
          toastr(error.message, "bg-danger");
        }
        this.page_state('list');
      },
      printer_filter() {
        this.printer_leaked = this.printer_listed;
      }
    },
  });
</script>