<div class="main-content" id="app">
  <div class="page-content" v-cloak>
    <div class="container-fluid mb-2">
      <div data-name="cabezera" class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-1 pt-1">
            <h4 class="mb-sm-0">{{page_title}}</h4>
          </div>
        </div>
      </div>
      <div data-name="notificaciones" class="row"><?php echo Flasher::flash() ?></div>
      <div data-name="listado" class="row" v-if="page_list">
        <div class="card">
          <div class="card-body p-2">
            <ul class="list-group list-group-flush overflow-auto">
              <li v-if="!message_leaked" class="list-group-item">
                <h6>No hay registros</h6>
              </li>
              <li v-if="message_leaked" v-for="(menssage, index) in message_leaked" v-show="(page_current - 1) * page_items <= index  && page_current * page_items > index" class="list-group-item">
                <div class="row g-1">
                  <div class="col-md-7">
                    <div class="d-flex align-items-start">
                      <p class="text-reset fs-14 mb-0 text-capitalize">{{menssage.message_name}}</p>
                    </div>
                    <div class="d-flex align-items-start">
                      <p class="text-muted mb-0">Correo Eletronico: <span class="fw-medium">{{menssage.message_email}}</span></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="d-flex align-items-start">
                      <p class="text-muted mb-0">Asunto: <span class="fw-medium text-secondary">{{menssage.message_subject}}</span></p>
                    </div>
                    <div class="d-flex align-items-start">
                      <p class="text-muted mb-0">Fecha: <span class="fw-medium text-secondary">{{menssage.message_date}}</span></p>
                    </div>
                  </div>
                  <div class="col-auto ms-auto">
                    <button @click="message_view(menssage)" class="btn btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#messages">
                      <i class="ri-eye-line ri-xl"></i>
                    </button>
                    <button @click="message_delete(menssage)" class="btn btn-icon text-danger">
                      <i class="ri-delete-bin-line ri-xl"></i>
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="message_leaked" class="card-footer">
            <div class="row">
              <div class="col-sm">
                <div class="text-muted">Total Paginas: {{page_current}}/<span class="fw-semibold">{{Math.ceil(message_leaked.length / page_items)}}</span></div>
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
                      <a class="page-link cursor-pointer" v-show="page_current * page_items / message_leaked.length < 1" @click.prevent="page_current += 1"><i class="ri-arrow-right-s-line ri-md align-middle"></i></a>
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
      <div data-name="error" v-if="page_error" class="row justify-content-between align-items-center text-center">
        <i class="ri-close-circle-line" style="font-size: 50px; color: red;"></i>
        <h3 class="mt-4 fw-semibold">Acceso denegado</h3>
      </div>
      <div data-name="spinner" v-if="page_spinner" class="row justify-content-center">
        <div class="loader"></div>
      </div>
    </div>
  </div>
  <div id="messages" class="modal fade" tabindex="-1" aria-labelledby="messagesLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div v-if="message_form" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="messagesLabel">{{message_form.message_name}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body">
          <h5 class="fs-15">
            {{message_form.message_subject}}
          </h5>
          <p class="text-muted">{{message_form.message_content}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
        </div>
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
      page_spinner: true,
      page_error: false,
      page_title: "Administracion de mensajes",
      page_current: 1,
      page_items: 10,

      message_search: "",
      message_listed: [],
      message_leaked: null,
      message_form: null,
    },
    mounted: function() {
      this.message_list();
    },
    watch: {
      page_list: function() {
        if (this.page_list) {
          this.page_spinner = false;
          this.page_error = false;
        }
      },
      page_spinner: function() {
        if (this.page_spinner) {
          this.page_error = false;
          this.page_list = false;
        }
      },
      page_error: function() {
        if (this.page_error) {
          this.page_spinner = false;
          this.page_list = false;
        }
      },
    },
    computed: {},
    methods: {
      message_delete: function(seleccion) {
        Swal.fire({
          text: `¿Seguro desea eliminar el mensaje seleccionado?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            const url = `${uri}messages/message_delete`;
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
                      this.message_listed = res.data.message_listed;
                      this.message_filter();
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
                toastr(`delete_messages ${error}`, "bg-danger");
              });
          }
          this.page_list = true;
        });
      },
      message_view: function(seleccion) {
        this.message_form = seleccion;
      },
      message_list: function() {
        const url = `${uri}messages/message_list`;
        let data = new FormData();
        data.append("csrf", csrf);

        axios
          .post(url, data)
          .then((response) => {
            let res = response.data;
            switch (res.status) {
              case 200:
                this.message_listed = res.data.message_listed;
                this.message_filter();
                break;
              default:
                get_action(res.status, res.msg);
                this.page_error = true;
                break;
            }
            this.page_list = true;
          })
          .catch(function(error) {
            toastr(`message_list ${error}`, "bg-danger");
          });
      },
      message_filter: function() {
        let array_list = this.message_listed;
        if (!array_list.length) return (this.message_leaked = false);
        this.message_leaked = array_list;
      },
    },
  });
</script>