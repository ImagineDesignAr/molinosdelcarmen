<div class="fixed-btn-group">
    <a href="javascript: void(0);" id="mode" class="mode-btn text-white rounded-start">
        <i class="mdi mdi-weather-sunny mode-light"></i>
        <i class="mdi mdi-moon-waning-crescent mode-dark"></i>
    </a>
    <a href="https://api.whatsapp.com/send?phone=5493435497786" id="whatsapp" class="whatsapp-btn text-white rounded-start">
        <i class="mdi mdi-whatsapp whatsapp-light"></i>
    </a>
</div>
<div id="preloader">
    <div id="status">
        <div class="sk-cube-grid">
            <div class="sk-cube sk-cube1"></div>
            <div class="sk-cube sk-cube2"></div>
            <div class="sk-cube sk-cube3"></div>
            <div class="sk-cube sk-cube4"></div>
            <div class="sk-cube sk-cube5"></div>
            <div class="sk-cube sk-cube6"></div>
            <div class="sk-cube sk-cube7"></div>
            <div class="sk-cube sk-cube8"></div>
            <div class="sk-cube sk-cube9"></div>
        </div>
    </div>
</div>
<div id="app">
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom navbar-light sticky sticky-dark" id="navbar">
        <div class="container">
            <a class="navbar-brand logo" href="<?php echo $site->index ?>">
                <img src="<?php echo $site->logo ?>" alt="" height="55" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto navbar-center" id="navbar-navlist">
                    <li class="nav-item active">
                        <a data-scroll href="#home" class="nav-link">Portada</a>
                    </li>
                    <li class="nav-item">
                        <a data-scroll href="#about" class="nav-link">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a data-scroll href="#contact" class="nav-link">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="section home-5-bg active" style="background-image:url('<?php echo $site->portada ?>');" id="home">
        <div class="bg-overlay"></div>
    </section>
    <section class="section bg-about bg-light-about bg-light" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-heading mb-5">
                        <h3 class="text-dark mb-1 fw-light text-uppercase franklin-demi">Nosotros</h3>
                        <div class="title-border-simple position-relative"></div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="about-img light-img position-relative p-4">
                        <img src="<?php echo $site->fabrica ?>" alt="" class="img-fluid mx-auto d-block" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-desc">
                        <h3 class="text-dark mb-3 fw-light franklin-demi">La Empresa</h3>
                        <p class="text-dark f-15 franklin-book">Somos una empresa nueva en el sector, con una trayectoria comercial en la localidad de Nogoyá de casi 80 años; teniendo como meta: volver a poner en total funcionamiento la planta del <strong>Viejo Molino Río de La Plata</strong> y que fuera originariamente Molinos San José de la familia Mihura; los cuales funcionaron desde principios de siglo XX, hasta su cierre en la década de los años 90'.</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-5">
                <div class="col-md-6">
                    <div class="about-desc">
                        <h3 class="text-dark mb-3 fw-light franklin-demi">Historia</h3>
                        <p class="text-dark f-15 franklin-book">Dichas instalaciones, estaban altamente deterioradas y casi sin mantenimiento. A fines del año 2007, fue comprado por un grupo familiar de la ciudad, el cual ha ido recuperando ciertas partes de la planta, con el sueño de poner en marcha la totalidad del establecimiento para la elaboración de <strong>Harina de Trigo</strong> y sus derivados. De esta forma, se han incorporado nueva tecnología en máquinas, con el objetivo de recuperar la Calidad, Cantidad y Variedad de los <strong>Productos</strong> que se elaboró en su momento.</p>
                        <p class="text-dark f-15 franklin-book">La planta, cuenta con una capacidad de almacenaje de 20.000 Tn. de granos, lo que ha permitido procesar en la etapa inaugural aproximadamente 60 Tn. de trigo por día, llegando a producir <strong>Semolín</strong> y <strong>Harinas</strong> de calidad <strong>000 y 0000</strong>, con sus correspondientes <strong>Aditivos y Vitaminas</strong>.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-img light-img position-relative p-4">
                        <img src="<?php echo $site->historia ?>" alt="" class="img-fluid mx-auto d-block" />
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-5">
                <div class="col-md-6">
                    <div class="about-img light-img position-relative p-4">
                        <img src="<?php echo $site->objetivos ?>" alt="" class="img-fluid mx-auto d-block" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-desc">
                        <h3 class="text-dark mb-3 fw-light franklin-demi">Objetivos</h3>
                        <p class="text-dark f-15 franklin-book">En una primera etapa, la empresa está en condiciones de ofrecer <strong>Afrechillo de Trigo</strong> para alimento animal, y en un corto plazo también <strong>Speller de Trigo</strong>, pudiendo desarrollar en un mediano plazo <strong>Molienda</strong> de 120 Tn.</p>
                        <p class="text-dark f-15 franklin-book">Teniendo por objetivo ser una alternativa más en la fabricación de alimentos en el <strong>Mercado Local e Internacional</strong>, ofertamos nuestros servicios a los diferentes rubros que necesiten nuestros productos, como productores de alimentos balanceados, consumidores de afrechillo, semolín, harina de trigo en envases de 25kg, con calidad 000 y 0000.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section bg-light" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-heading mb-5">
                        <h3 class="text-dark mb-1 fw-light text-uppercase franklin-demi">Contáctanos</h3>
                        <div class="title-border-simple position-relative"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-box p-5">
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="custom-form p-3">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3 app-label">
                                                    <input v-model="message_form.message_name"  name="name" id="name" type="text" class="form-control" placeholder="Nombre" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3 app-label">
                                                    <input v-model="message_form.message_email" name="email" id="email" type="email" class="form-control" placeholder="Correo electrónico" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3 app-label">
                                                    <input v-model="message_form.message_subject" type="text" class="form-control" id="subject" placeholder="Asunto" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3 app-label">
                                                    <textarea v-model="message_form.message_content" name="comments" id="comments" rows="5" class="form-control" placeholder="Mensaje"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input @click="message_send()" type="button" id="submit" name="send" class="submitBnt btn btn-custom" value="Enviar Mensaje" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="contact-cantent p-3">
                                    <div class="contact-details">
                                        <div class="float-start contact-icon me-3 mt-2">
                                            <i class="mdi mdi-phone text-dark h5"></i>
                                        </div>
                                        <div class="app-contact-desc text-dark pt-1">
                                            <p class="mb-0 info-title f-13">Teléfono :</p>
                                            <p class="mb-0 f-13">+54 15 3435497786</p>
                                        </div>
                                    </div>

                                    <div class="contact-details mt-2">
                                        <div class="float-start contact-icon me-3 mt-2">
                                            <i class="mdi mdi-email-outline text-dark h5"></i>
                                        </div>
                                        <div class="app-contact-desc text-dark pt-1">
                                            <p class="mb-0 info-title f-13">Correo :</p>
                                            <p class="mb-0 f-13"><a href="" class="text-dark">molinosdelcarmen@interya.net.ar</a></p>
                                        </div>
                                    </div>

                                    <div class="contact-details mt-2">
                                        <div class="float-start contact-icon me-3 mt-2">
                                            <i class="mdi mdi-map-marker text-dark h5"></i>
                                        </div>
                                        <div class="app-contact-desc text-dark pt-1">
                                            <p class="mb-0 info-title f-13">Ubicación :</p>
                                            <p class="mb-0 f-13"><a href="" class="text-dark">Tristan Frutos y J. B. Mihura - Nogoyá - Entre Rios</a></p>
                                        </div>
                                    </div>

                                    <div class="follow mt-4">
                                        <h4 class="text-dark mb-3">Síguenos</h4>
                                        <ul class="follow-icon list-inline mt-32 mb-0">
                                            <li class="list-inline-item f-15">
                                                <a href="https://www.instagram.com/molinosdelcarmen/" class="social-icon text-dark"><i class="mdi mdi-instagram"></i></a>
                                            </li>
                                            <li class="list-inline-item f-15">
                                                <a href="https://api.whatsapp.com/send?phone=5493435497786" class="social-icon text-dark"><i class="mdi mdi-whatsapp"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="footer-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-5">
                        <p class="text-uppercase text-dark footer-title mb-4">Sobre Nosotros</p>
                        <p class="text-dark f-14">Somos una empresa familiar dedicada a la producción de harina de trigo con más de 15 años en el rubro.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-5">
                                <p class="text-uppercase text-dark footer-title mb-4">Empresa</p>
                                <ul class="list-unstyled footer-sub-menu">
                                    <li class="f-14"><a href="https://www.instagram.com/p/DIJ8DC8xT0a/?img_index=1" class="text-dark">Ubicación y Logistica</a></li>
                                    <li class="f-14"><a href="https://www.instagram.com/p/DG5mA6pOv6A/?img_index=1" class="text-dark">Nuestra Planta</a></li>
                                    <li class="f-14"><a href="https://www.instagram.com/p/DFK8i1suTqX/?img_index=1" class="text-dark">Historia</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="footer-alt bg-dark pt-3 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="copyright text-white f-14 fw-light mb-0">
                        2025 © Molinos del Carmen. Design By Innovacion Digital
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    var vm = new Vue({
        el: "#app",
        data: {
            page_index: true,
            message_form: {
                message_name: "",
                message_email: "",
                message_subject: "",
                message_content: "",
            },
            blog_form: null,
            blog_listed: null,
            article_listed: null,
        },
        mounted() {
            this.get_page();
        },
        computed: {},
        watch: {
            page_index: function() {
                if (this.page_index) {
                    this.page_history = false;
                }
            },
            page_history: function() {
                if (this.page_history) {
                    topFunction();
                    this.page_index = false;
                }
            },
        },
        methods: {
            async get_page() {
                try {
                    // !sessionStorage.local_order ? this.new_order() : (this.local_order = JSON.parse(sessionStorage.local_order));
                    const url = `${uri}landing/page_data`;
                    let data = new FormData();
                    data.append("csrf", csrf);

                    const response = await axios.post(url, data);
                    const res = response.data;

                    if (res.status === 200) {
                        this.blog_listed = res.data.blog_listed;
                        this.article_listed = res.data.article_listed;
                    } else {
                        get_action(res.status, res.msg);
                    }
                    this.page_index = true;
                } catch (error) {
                    toastr(error.message, "bg-danger");
                }
            },
            blog_view: function(seleccion) {
                this.blog_form = seleccion;
            },
            message_send: function() {
                if (!this.message_form.message_name) {
                    toastr("Debe ingresar su nombre completo.", "bg-danger");
                    return;
                }
                if (!this.message_form.message_email) {
                    toastr("Debe ingresar un correo electronico de contacto.", "bg-danger");
                    return;
                }
                if (!this.message_form.message_subject) {
                    toastr("Debe ingresar un asunto de contacto.", "bg-danger");
                    return;
                }
                if (!this.message_form.message_content) {
                    toastr("Debe ingresar un mensaje.", "bg-danger");
                    return;
                }

                let data = new FormData();
                data.append("csrf", csrf);
                data.append("form", JSON.stringify(this.message_form));

                url = `${uri}landing/send`;
                axios
                    .post(url, data)
                    .then((response) => {
                        let res = response.data;
                        switch (res.status) {
                            case 200:
                                if (res.data != []) {
                                    toastr(res.msg, "bg-success");
                                    this.message_form.message_name = "";
                                    this.message_form.message_email = "";
                                    this.message_form.message_subject = "";
                                    this.message_form.message_content = "";
                                }
                                break;
                            default:
                                get_action(res.status, res.msg);
                                break;
                        }
                    })
                    .catch(function(error) {
                        toastr(`Hubo un error en la petición send_comment ${error}`, "bg-danger");
                    });
            },
        },
    });
</script>