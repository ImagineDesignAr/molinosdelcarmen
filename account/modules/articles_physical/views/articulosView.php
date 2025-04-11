<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid">
      <div data-name="botonera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page_title}}</h4>
            <div class="page-title-right">
              <button @click="physical_new()" v-if="page_list" type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"><i class="ri-add-line"></i> Nuevo</button>
              <button @click.esc="physical_close();" v-if="page_form" type="button" class="btn btn-ghost-danger btn-sm waves-effect waves-light"><i class="ri-close-line me-1 align-middle"></i> Volver</button>
              <button @click="physical_save()" v-if="page_form" type="button" class="btn btn-outline-success btn-sm"><i class="ri-check-line me-1 align-middle"></i> Guardar</button>
            </div>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="borradores" v-if="drafts_lists.length && page_list" class="row mb-2">
        <div class="card">
          <div class="card-header align-items-center d-flex p-1">
            <h4 class="card-title mb-0 flex-grow-1">Borradores</h4>
            <div class="flex-shrink-0">
              <button @click="drafts_empty();" type="button" class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                <i class="bx bx-trash-alt label-icon align-middle fs-16 me-2"></i> Vaciar Borradores
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush border-dashed overflow-auto" style="max-height:250px">
              <li v-for="(physical, index) in drafts_lists" v-if="drafts_lists" class="list-group-item pt-1 pb-1">
                <div class="row g-1">
                  <div class="col-xl-3 d-flex align-items-center">
                    <img :src="physical.physical_picture" alt="" class="rounded-circle avatar-sm me-2">
                    <div>
                      <p class="text-reset fs-14 mb-0 text-capitalize">{{physical.brand_name}} {{physical.physical_title}}</p>
                      <p class="text-muted mb-0">{{physical.physical_presentation}}
                        <span class="badge border border-dark text-body">{{physical.physical_sku}}</span>
                        <span class="badge border border-dark text-body">{{physical.physical_article}}</span>
                      </p>
                    </div>
                  </div>
                  <div class="col-xl-3">
                    <p class="fw-16">{{physical.physical_description}}</p>
                  </div>
                  <div class="col-auto ms-auto">
                    <div class="row">
                      <div class="d-flex align-items-center">
                        <div>
                          <p class="mb-0">Precio Venta: <span class="fs-22 fw-semibold ff-secondary mb-4">$ {{physical.physical_price}}</span></p>
                          <p class="mb-0">Precio Oferta: <span class="fs-22 fw-semibold ff-secondary mb-4">$ {{physical.physical_offer_price}}</span></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-2">
                    <div class="d-flex align-items-center justify-content-end">
                      <button class="btn btn-icon text-primary" @click="physical_view(physical)">
                        <i class="ri-edit-2-line ri-xl"></i>
                      </button>
                      <button class="btn btn-icon text-danger" @click="physical_delete(physical)">
                        <i class="ri-delete-bin-line ri-xl"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div data-name="listado_articulos" v-if="page_list" class="row mb-2">
        <div class="card">
          <div class="card-header align-items-center p-2">
            <div class="row mt-1">
              <div class="col-md-6">
                <label class="small strong" for="physical_search">Criterio de busqueda</label>
                <div class="input-group">
                  <input v-model="physical_search" :class="control_form" id="physical_search" type="text" class="form-control me-1">
                  <button @click="physical_search=''" :class="control_btn" type="button" class="btn btn-outline-primary waves-effect waves-light">
                    <i class="ri-delete-back-2-line ri-xl align-middle"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-2">
                <label class="small strong" for="brand_name">Marca</label>
                <div class="input-group">
                  <select v-model="brand_selected" @change="physical_filter" :class="control_form" class="form-control text-uppercase me-1" id="brand_name">
                    <option value="all">TODOS</option>
                    <option :value="marca.brand_id" v-for="(marca, index) in brand_listed">{{marca.brand_name}}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <label class="small strong" for="category_input">Categorias</label>
                <div class="input-group">
                  <select v-model="category_selected" @change="physical_filter" :class="control_form" id="category_selected" class="form-control text-uppercase me-1">
                    <option value="all">TODOS</option>
                    <option :value="categoria.category_id" v-for="(categoria, index) in category_listed" v-if="categoria.category_class == 'physical'">{{categoria.category_name}}</option>
                  </select>
                  <button @click="category_delete()" v-if="category_listed" :class="control_btn" class="btn btn-outline-danger me-1" type="button"><i class="ri-delete-bin-line ri-xl align-middle"></i></button>
                </div>
              </div>
              <div class="col-md-2">
                <label class="small strong" for="category_input">Nueva Categoria</label>
                <div class="input-group">
                  <input @keyup.enter="category_add()" v-model="category_input" :class="control_form" id="category_input" type="text" class="form-control me-1" aria-label="Agregue categorias desde aqui">
                  <button @click="category_add()" :class="control_btn" class="btn btn-outline-primary" type="button"><i class="ri-menu-add-line ri-xl align-middle"></i></button>
                </div>
              </div>

            </div>
          </div>
          <div class="card-body p-1">
            <ul class="list-group list-group-flush border-dashed overflow-auto">
              <li class="list-group-item" v-if="!physical_leaked">
                <h6>No hay registros</h6>
              </li>
              <li v-for="(physical, index) in physical_leaked" v-show="(page_current - 1) * page_items <= index  && page_current * page_items > index" v-if="physical_leaked && physical.physical_condition != 3" class="list-group-item pt-1 pb-1">
                <div class="row g-1">
                  <div class="col-xl-4 d-flex align-items-center">
                    <img :src="physical.physical_picture" alt="" class="rounded-circle avatar-sm me-2">
                    <div>
                      <p class="text-reset fs-12 mb-0">{{physical.brand_name}} {{physical.physical_title}}</p>
                      <p class="text-muted mb-0">{{physical.physical_presentation}}</p>
                      <span class="badge border border-dark text-body pe-1">{{physical.physical_attribute}} {{physical.measure_name}}</span>
                      <span class="badge border border-dark text-body pe-1">{{physical.physical_sku}}</span>
                      <span class="badge border border-dark text-body fw-bold">{{physical.physical_article}}</span>
                    </div>
                  </div>
                  <div class="col-xl">
                    <p class="small">{{physical.physical_description}}</p>
                  </div>
                  <div class="col-xl-3 ms-auto" hidden>
                    <div class="row">
                      <div class="d-flex align-items-center">
                        <div>
                          <p class="mb-0">Precio Venta: <span class="fs-22 fw-semibold ff-secondary mb-4">$ {{physical.physical_price}}</span></p>
                          <p class="mb-0">Precio Oferta: <span class="fs-22 fw-semibold ff-secondary mb-4">$ {{physical.physical_offer_price}}</span></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-2 ms-auto">
                    <div class="d-flex align-items-center justify-content-end">
                      <span class="badge me-1" :class="physical.condition_color">{{physical.article_condition_name}}</span>
                      <button class="btn btn-icon text-dark" @click="physical_group(physical)">
                        <i class="ri-recycle-line ri-xl"></i>
                      </button>
                      <button class="btn btn-icon text-primary" @click="physical_view(physical)">
                        <i class="ri-edit-2-line ri-xl"></i>
                      </button>
                      <button class="btn btn-icon text-danger" @click="physical_delete(physical)">
                        <i class="ri-delete-bin-line ri-xl"></i>
                      </button>
                      <div class="form-check form-switch form-switch-success" :id="physical.physical_article">
                        <input v-model="physical.article_available" @click="article_available(physical)" class="form-check-input" type="checkbox" role="switch" :name="physical.physical_article" checked>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="physical_leaked" class="card-footer">
            <div class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page_current}}/<span class="fw-semibold">{{Math.ceil(physical_leaked.length / page_items)}}</span></div>
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
                      <a class="page-link cursor-pointer" v-show="page_current * page_items / physical_leaked.length < 1" @click.prevent="page_current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
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
      <div data-name="formulario_articulos" v-if="page_form" class="row mb-2">
        <div class="col-xl-3">
          <div class="card h-100">
            <div class="card-header p-1">
              <div class="row">
                <div class="col">
                  <div class="form-check form-switch form-switch-dark">
                    <input v-model="physical_form.physical_local" type="checkbox" role="switch" id="physical_local" class="form-check-input" v-model="physical_form.physical_local">
                    <label for="physical_local" class="form-check-label">Local</label>
                  </div>
                </div>
                <div class="col-auto ms-auto">
                  <div class="form-check form-switch form-switch-dark">
                    <input v-model="physical_form.physical_web" type="checkbox" role="switch" id="physical_web" class="form-check-input" v-model="physical_form.physical_web">
                    <label for="physical_web" class="form-check-label">Web</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col text-center">
                  <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                    <img :src="physical_form.physical_picture" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                      <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="picture_upload()" ref="profile_picture">
                      <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                        <span class="avatar-title rounded-circle bg-light text-body">
                          <i class="ri-camera-fill"></i>
                        </span>
                      </label>
                    </div>
                  </div>
                  <h5 class="fs-16 mb-1">Imagen Principal</h5>
                </div>
              </div>
              <div class="row">
                <label class="fs-15 strong" for="brand_name">Marca</label>
                <div class="input-group" v-if="!brand_new">
                  <select class="form-control text-uppercase me-1" v-model="physical_form.physical_brand_id" id="brand_name">
                    <option :value="marca.brand_name" v-for="(marca, index) in brand_listed">{{marca.brand_name}}</option>
                  </select>
                  <button @click="brand_new = true" class="btn btn-outline-success me-1" type="button"><i class="ri-add-circle-line ri-xl align-middle"></i></button>
                </div>
                <div class="input-group" v-if="brand_new">
                  <input v-model="physical_form.physical_brand_id" type="text" class="form-control text-uppercase me-1" id="physical_brand_id" aria-label="Nueva marca">
                  <button @click="brand_new = false" class="btn btn-outline-danger me-1" type="button"><i class="ri-close-circle-line ri-xl align-middle"></i></button>
                </div>
              </div>
              <div class="row">
                <label class="fs-15 strong" for="category">Categoria</label>
                <select class="form-control text-uppercase" v-model="physical_form.category" id="category" multiple>
                  <option :value="categoria.category_id" v-for="(categoria, index) in category_listed" v-if="categoria.category_class == 'physical'">{{categoria.category_name}}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-9">
          <div class="card h-100">
            <div class="card-header">
              <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item" role="datos">
                  <a class="nav-link active" data-bs-toggle="tab" href="#datos" role="tab" aria-selected="true">
                    Datos Articulo
                  </a>
                </li>
              </ul>
            </div>
            <div class="card-body p-2">
              <div class="tab-content">
                <div class="tab-pane active" id="datos" role="tabpanel" aria-label="datos">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="physical_title" class="form-label">Titulo principal</label>
                        <input type="text" class="form-control" id="physical_title" placeholder="" v-model="physical_form.physical_title">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="physical_presentation" class="form-label">Presentacion</label>
                        <input type="text" class="form-control" id="physical_presentation" placeholder="" v-model="physical_form.physical_presentation">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="physical_attribute" class="form-label">Atributo</label>
                        <input type="text" class="form-control" id="physical_attribute" placeholder="" v-model="physical_form.physical_attribute">
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="mb-3">
                        <label for="physical_measure_id" class="form-label">Unidad de Medida</label>
                        <select class="form-control" id="physical_measure_id" v-model="physical_form.physical_measure_id">
                          <option value="1">Unidad</option>
                          <option value="2">Kilos</option>
                          <option value="3">Gramos</option>
                          <option value="4">Litros</option>
                          <option value="5">Mililitros</option>
                          <option value="6">Centimentros Cubitos</option>
                          <option value="7">Centimentros</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="mb-3 pb-2">
                        <label for="physical_description" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="physical_description" placeholder="Breve descripcion del articulo." rows="3" v-model="physical_form.physical_description"></textarea>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="physical_sku" class="form-label">SKU</label>
                        <input type="text" id="physical_sku" placeholder="E0001" class="form-control" v-model="physical_form.physical_sku">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="physical_barcode" class="form-label">Codigo Barras</label>
                        <input type="text" id="physical_barcode" placeholder="78554956623" class="form-control" v-model="physical_form.physical_barcode">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="venta_maxima" class="form-label">Venta Maxima</label>
                        <input type="text" id="venta_maxima" placeholder="1" class="form-control" v-model="physical_form.physical_maxsale">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-3">
                        <label for="venta_minima" class="form-label">Venta Minima</label>
                        <input type="text" id="venta_minima" placeholder="1" class="form-control" v-model="physical_form.physical_minsale">
                      </div>
                    </div>
                    <div class="col-lg-8">
                      <div class="mb-3">
                        <label for="physical_metadata" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="physical_metadata" placeholder="Meta Keywords" v-model="physical_form.physical_metadata">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="physical_condition" class="form-label">Condicion</label>
                        <select v-model="physical_form.physical_condition" id="physical_condition" class="form-control text-uppercase">
                          <option value="3">Borrador</option>
                          <option value="1">Activo</option>
                          <option value="2">Inactivo</option>
                          <option value="4">Discontinuado</option>
                          <option value="5">Sin Stock</option>
                          <option value="0">Eliminado</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-auto me-2 ms-2 ms-auto">
                      <div class="form-check form-switch form-switch-dark">
                        <input v-model="physical_form.article_available" type="checkbox" role="switch" id="article_available" class="form-check-input" v-model="physical_form.article_available">
                        <label for="article_available" class="form-check-label">Disponible</label>
                      </div>
                    </div>
                    <div class="col-auto me-2 ms-2">
                      <div class="form-check form-switch form-switch-dark">
                        <input v-model="physical_form.article_stock" type="checkbox" role="switch" id="article_stock" class="form-check-input" v-model="physical_form.article_stock">
                        <label for="article_stock" class="form-check-label">Administrar Stock</label>
                      </div>
                    </div>
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
    mounted: function() {
      this.category_list();
      this.brand_list();
      this.drafts_list();
      this.physical_list();
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
          topFunction();
          this.page_spinner = false;
          this.page_error = false;
          this.page_list = false;
        }
      },
      page_error: function() {
        if (this.page_error) {
          this.page_spinner = false;
          this.page_list = false;
          this.page_form = false;
        }
      },
      page_spinner: function() {
        if (this.page_spinner) {
          this.page_error = false;
          this.page_list = false;
          this.page_form = false;
        }
      },
      physical_search: function() {
        this.physical_filter();
      },
    },
    computed: {},
    methods: {
      refresh: function() {
        this.brand_new = false;
        this.brand_list();
        this.drafts_list();
        this.physical_list();
      },
      /* FUNCIONES GLOBALES */
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
                  this.physical_form.physical_picture = res.data;
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
      calculate_price: function() {
        const {
          physical_cost,
          physical_tax,
          physical_utility,
          physical_automatic_price
        } = this.physical_form;
        let costo = comprobar_nan(physical_cost);
        if (physical_automatic_price) {
          let calculate_utility = profitability(costo, comprobar_nan(physical_utility));
          let calculate_tax = profitability(costo + calculate_utility, comprobar_nan(physical_tax));

          this.physical_form.physical_price = (costo + calculate_utility + calculate_tax).toFixed(2);
        }
      },
      /* FUNCIONES GLOBALES */
      /* MANEJO DE TODO LO RELACIONADO A LOS ARTICULOS */
      article_available: function(physical) {
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
          .catch(function(error) {
            toastr(`Hubo un error en la petición articleNew ${error}`, "bg-danger");
          });
      },
      physical_filter: function() {
        let text = this.physical_search.trim().toLowerCase();
        this.physical_leaked = this.physical_listed.filter((item) => item.physical_metadata.includes(text) && (this.category_selected === "all" || item.category_id === this.category_selected) && (this.brand_selected === "all" || item.physical_brand_id === this.brand_selected));
        // Si no hay coincidencias, asigna `false`
        this.physical_leaked = this.physical_leaked.length > 0 ? this.physical_leaked : false;
        // Activa la paginación
        this.page_list = true;
      },
      physical_list: function() {
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
          .catch(function(error) {
            toastr(`physical_list ${error}`, "bg-danger");
          });
      },
      physical_new: function() {
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
          .catch(function(error) {
            toastr(`physical_new ${error}`, "bg-danger");
          });
      },
      physical_save: function() {
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
          .catch(function(error) {
            toastr(`Hubo un error en la petición articleSave ${error}`, "bg-danger");
          });
      },
      physical_view: function(physical) {
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
          .catch(function(error) {
            toastr(`Hubo un error en la petición articles_view ${error}`, "bg-danger");
          });
      },
      physical_group: function(physical) {
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
          .catch(function(error) {
            toastr(`physical_group ${error}`, "bg-danger");
          });
      },
      physical_delete: function(physical) {
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
              .catch(function(error) {
                toastr(`Hubo un error en la petición articleDelete ${error}`, "bg-danger");
              });
          }
        });
      },
      physical_close: function() {
        this.drafts_list();
        this.physical_list();
        this.physical_form = [];
        this.page_list = true;
      },
      drafts_list: function() {
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
          .catch(function(error) {
            toastr(`drafts_list ${error}`, "bg-danger");
          });
      },
      drafts_empty: function() {
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
              .catch(function(error) {
                toastr(`Hubo un error en la petición articleSave ${error}`, "bg-danger");
              });
          }
        });
      },
      /* MANEJO DE TODO LO RELACIONADO A LOS ARTICULOS */

      /* MANEJO DE TODO LO RELACIONADO A LAS CATEOGORIAS */
      category_add: function() {
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
          .catch(function(error) {
            toastr(`category_add ${error}`, "bg-danger");
          });
      },
      category_delete: function() {
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
              .catch(function(error) {
                toastr(`Hubo un error en la petición addCategoria ${error}`, "bg-danger");
              });
          }
        });
      },
      category_list: function() {
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
          .catch(function(error) {
            toastr(`drafts_list ${error}`, "bg-danger");
          });
      },
      /* MANEJO DE TODO LO RELACIONADO A LAS MARCAS */
      brand_list: function() {
        let data = new FormData();
        data.append("csrf", csrf);

        axios
          .post(`${uri}brand/brand_list`, data)
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
          .catch(function(error) {
            toastr(`drafts_list ${error}`, "bg-danger");
          });
      },
    },
  });
</script>