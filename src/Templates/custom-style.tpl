

  {{-- Custom Style --}}
  <style id="custom-style">
    .swagger-ui .topbar, .swagger-ui .opblock-tag:hover {
        background-color: {{config('l5-swagger.css.top_bar_color')}};
    }

    .information-container.wrapper, .download-url-wrapper, a.link span {
        display: none !important;
    }

    .swagger-ui .scheme-container {
        background: {{config('l5-swagger.css.main_color')}};
    }

    body {
        background-color: {{config('l5-swagger.css.body_color')}};
    }

    .swagger-ui .btn.authorize svg {
        fill: #ffb612 !important;
    }

    .swagger-ui .btn.authorize {
        color: #ffb612 !important;
        border-color: #ffb612;
    }

    .swagger-ui .opblock-tag {
        color: white;
        background-color: {{config('l5-swagger.css.main_color')}};
    }


    .swagger-ui .opblock.opblock-get {
        border-color: {{config("l5-swagger.css.get")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.get"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.get"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.get"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-get .opblock-summary-method {
        background: {{config("l5-swagger.css.get")}};
    }
    .swagger-ui .opblock.opblock-get .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.get")}};
    }
    .swagger-ui .opblock.opblock-get .opblock-summary {
        border-color: {{config("l5-swagger.css.get")}};
    }


    .swagger-ui .opblock.opblock-post {
        border-color: {{config("l5-swagger.css.post")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.post"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.post"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.post"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-post .opblock-summary-method {
        background: {{config("l5-swagger.css.post")}};
    }
    .swagger-ui .opblock.opblock-post .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.post")}};
    }
    .swagger-ui .opblock.opblock-post .opblock-summary {
        border-color: {{config("l5-swagger.css.post")}};
    }


    .swagger-ui .opblock.opblock-put {
        border-color: {{config("l5-swagger.css.put")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.put"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.put"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.put"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-put .opblock-summary-method {
        background: {{config("l5-swagger.css.put")}};
    }
    .swagger-ui .opblock.opblock-put .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.put")}};
    }
    .swagger-ui .opblock.opblock-put .opblock-summary {
        border-color: {{config("l5-swagger.css.put")}};
    }


    .swagger-ui .opblock.opblock-delete {
        border-color: {{config("l5-swagger.css.delete")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.delete"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.delete"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.delete"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-delete .opblock-summary-method {
        background: {{config("l5-swagger.css.delete")}};
    }
    .swagger-ui .opblock.opblock-delete .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.delete")}};
    }
    .swagger-ui .opblock.opblock-delte .opblock-summary {
        border-color: {{config("l5-swagger.css.delete")}};
    }


    .swagger-ui .opblock.opblock-head {
        border-color: {{config("l5-swagger.css.head")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.head"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.head"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.head"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-head .opblock-summary-method {
        background: {{config("l5-swagger.css.head")}};
    }
    .swagger-ui .opblock.opblock-head .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.head")}};
    }
    .swagger-ui .opblock.opblock-head .opblock-summary {
        border-color: {{config("l5-swagger.css.head")}};
    }


    .swagger-ui .opblock.opblock-patch {
        border-color: {{config("l5-swagger.css.patch")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.patch"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.patch"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.patch"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-patch .opblock-summary-method {
        background: {{config("l5-swagger.css.patch")}};
    }
    .swagger-ui .opblock.opblock-patch .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.patch")}};
    }
    .swagger-ui .opblock.opblock-patch .opblock-summary {
        border-color: {{config("l5-swagger.css.patch")}};
    }


    .swagger-ui .opblock.opblock-options {
        border-color: {{config("l5-swagger.css.options")}};
        background: rgba({{hexdec(substr(config("l5-swagger.css.options"), 1, 2)).",".hexdec(substr(config("l5-swagger.css.options"), 3, 2)).",".hexdec(substr(config("l5-swagger.css.options"), 5, 2)).",.8"}});
    }
    .swagger-ui .opblock.opblock-options .opblock-summary-method {
        background: {{config("l5-swagger.css.options")}};
    }
    .swagger-ui .opblock.opblock-options .tab-header .tab-item.active h4 span:after {
        background: {{config("l5-swagger.css.options")}};
    }
    .swagger-ui .opblock.opblock-options .opblock-summary {
        border-color: {{config("l5-swagger.css.options")}};
    }


    .swagger-ui .model-box {
        background: #41444e !important;
    }
    .swagger-ui .model {
        font-size: 13px !important;
        color: white !important;
    }

    .swagger-ui .btn.execute {
        background-color: #ffb612;
        color: #212121;
        border-color: #ffb612;
    }

    .swagger-ui .btn.cancel {
        background-color: #ff6060;
        color: white;
    }

    .swagger-ui .opblock-description-wrapper p, .swagger-ui .opblock-external-docs-wrapper p, .swagger-ui .opblock-title_normal p {
        font-size: 12px;
        color: #DDDDDD;
    }

    .swagger-ui .prop-type {
        color: #B10DC9;
    }
  </style>
  {{-- Custom Style end --}}