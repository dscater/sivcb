<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orden de Ventas - <span>Ticket</span></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="principal">
                                    <div
                                        class="contenedor_factura mx-auto"
                                        id="contenedor_imprimir"
                                    >
                                        <div class="elemento logo">
                                            <!-- <img
                                                src="{{ asset('imgs/' . $empresa->logo) }}"
                                                alt="Logo"
                                            /> -->
                                        </div>
                                        <div class="elemento nom_empresa">
                                            {{ oConfiguracion.razon_social }}
                                        </div>
                                        <div class="elemento nom_empresa">
                                            {{ oVenta.sucursal?.nombre }}
                                        </div>
                                        <div class="elemento direccion">
                                            Dirección:
                                            {{ oConfiguracion.dir }}
                                        </div>
                                        <div class="elemento direccion">
                                            Teléfonos:
                                            {{ oConfiguracion.fono }}
                                        </div>
                                        <div class="elemento direccion">
                                            La Paz - Bolivia
                                        </div>
                                        <div class="elemento direccion">
                                            ORDEN DE VENTA<br />
                                        </div>
                                        <div class="elemento bt">
                                            Número de Orden de Venta:
                                            {{ nro_factura }}
                                        </div>
                                        <div class="elemento detalle izquierda">
                                            Fecha y hora:{{
                                                oVenta.fecha_hora_t
                                            }}
                                            <br />
                                            NOMBRE:
                                            {{ oVenta.cliente?.nombre }}
                                            <br />
                                            NIT/C.I.: {{ oVenta.nit }}
                                            <br />
                                            Usu: {{ oVenta.user?.usuario }}
                                            <br />
                                        </div>
                                        <div class="elemento bold">DETALLE</div>
                                        <div class="cobro">
                                            <table>
                                                <tr class="punteado">
                                                    <td class="centreado">
                                                        CAN.
                                                    </td>
                                                    <td class="centreado">
                                                        COD.
                                                    </td>
                                                    <td class="centreado">
                                                        PRODUCTO
                                                    </td>
                                                    <td class="centreado">
                                                        P/U
                                                    </td>
                                                    <td class="centreado">
                                                        SUBTOTAL
                                                    </td>
                                                </tr>
                                                <tr
                                                    v-for="item in oVenta.venta_detalles"
                                                >
                                                    <td class="centreado">
                                                        {{ item.cantidad }}
                                                    </td>
                                                    <td class="centreado">
                                                        <span
                                                            v-for="(
                                                                pb, index_pb
                                                            ) in item.producto_barras"
                                                            >{{ pb.codigo }}
                                                            <span
                                                                v-if="
                                                                    item
                                                                        .producto_barras
                                                                        .length >
                                                                        1 &&
                                                                    index_pb <
                                                                        item
                                                                            .producto_barras
                                                                            .length -
                                                                            1
                                                                "
                                                                >|</span
                                                            ></span
                                                        >
                                                    </td>
                                                    <td class="izquierda">
                                                        {{
                                                            item.producto
                                                                ?.nombre
                                                        }}
                                                    </td>
                                                    <td class="centreado">
                                                        {{ item.precio }}
                                                    </td>
                                                    <td class="centreado">
                                                        {{ item.subtotal }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="bold elemento detalle"
                                                        style="
                                                            text-align: right;
                                                            padding-right: 4px;
                                                        "
                                                    >
                                                        TOTAL
                                                    </td>
                                                    <td
                                                        class="centreado bold detalle"
                                                        style=""
                                                    >
                                                        {{ oVenta.total }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="bold elemento"
                                                        style="
                                                            text-align: right;
                                                            padding-right: 4px;
                                                        "
                                                    >
                                                        DESCUENTO %
                                                    </td>
                                                    <td class="centreado bold">
                                                        {{ oVenta.descuento }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="bold elemento detalle"
                                                        style="
                                                            text-align: right;
                                                            padding-right: 4px;
                                                        "
                                                    >
                                                        TOTAL FINAL
                                                    </td>
                                                    <td
                                                        class="centreado bold detalle"
                                                    >
                                                        {{ oVenta.total_final }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="centreado literal">
                                            Son: {{ literal }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mx-auto">
                                        <button
                                            class="btn btn-primary btn-block btn-flat w-100"
                                            id="btnImprimir"
                                            @click="preparaImpresion"
                                            :disabled="imprimiendo"
                                        >
                                            <i class="fa fa-print"></i> Imprimir
                                        </button>
                                        <Link
                                            class="btn btn-outline-primary mt-2 btn-flat mb-1 btn-block w-100"
                                            :href="
                                                route('ventas.edit', venta.id)
                                            "
                                        >
                                            Editar
                                        </Link>
                                        <Link
                                            class="btn btn-outline-secondary mt-2 btn-flat mb-1 btn-block w-100"
                                            :href="route('ventas.index')"
                                        >
                                            Volver
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import { Link } from "@inertiajs/vue3";
export default {
    props: ["venta", "imprime", "nro_factura", "literal"],
    components: {
        Link,
    },
    data() {
        return {
            oConfiguracion: {
                id: 0,
                nombre_sistema: "",
                alias: "",
                razon_social: "",
                nit: "",
                ciudad: "",
                dir: "",
                fono: "",
                web: "",
                actividad: "",
                correo: "",
                logo: "",
            },
            oVenta: {
                id: 0,
                sucursal_id: "",
                cliente_id: "",
                nit: "",
                venta_mayor: "NO",
                total: "0.00",
                venta_detalles: [],
            },
            total_final: 0,
            devolucion: null,
            total_cantidad_devolucion: 0,
            imprimiendo: false,
        };
    },
    watch: {
        oVenta(newVal) {
            this.oVenta = newVal;
        },
    },
    mounted() {
        this.oVenta = this.venta;
        this.getConfiguracion();
        if (this.imprime && this.imprime == "true") {
            setTimeout(() => {
                this.preparaImpresion();
            }, 500);
        }
    },
    methods: {
        getConfiguracion() {
            this.loading = true;
            let url = route("configuracions.getConfiguracion");
            axios.get(url).then((res) => {
                this.oConfiguracion = res.data.configuracion;
            });
        },
        preparaImpresion() {
            this.imprimiendo = true;
            this.imrpimirContenedor();
        },
        imrpimirContenedor() {
            var divContents = document.getElementById("principal").innerHTML;
            var a = window.open("", "");
            a.document.write("<html>");
            a.document.write("<head>");
            a.document.write(
                `
                <style>
                    @page { margin: 0;}
                    #principal{
                        width: 4.5cm !important;
                    }

                    #contenedor_imprimir {
                        font-size: 8pt;
                        width: 4.5cm !important;
                        padding-top: 15px;
                        padding-bottom: 15px;
                        font-family: 'Times New Roman', Times, serif;
                    }

                    .elemento {
                        text-align: center;
                        font-size: 0.8em;
                    }

                    .elemento.logo img {
                        width: 60%;
                    }

                    .separador {
                        padding: 0px;
                        margin: 0px;
                    }

                    .fono,
                    .lp {
                        font-size: 0.7em;
                    }

                    .txt_fo {
                        margin-top: 3px;
                        border-top: dashed 1px black;
                    }

                    .detalle {
                        border-top: dashed 1px black;
                        border-bottom: dashed 1px black;
                    }
                    .bt {
                        border-top: solid 1px black;
                    }

                    .act_eco {
                        font-size: 0.7em;
                    }

                    .info1 {
                        text-align: center;
                        font-weight: bold;
                        font-size: 0.7em;
                    }

                    .info2 {
                        text-align: center;
                        font-weight: bold;
                        font-size: 0.7em;
                    }

                    .izquierda {
                        text-align: left;
                        padding-left: 5px;
                    }

                    .derecha {
                        text-align: right;
                        padding-right: 5px;
                    }

                    .informacion {
                        padding: 5px;
                        width: 100%;
                    }

                    .bold {
                        font-weight: bold;
                    }

                    .cobro {
                        width: 100%;
                        padding: 5px;
                    }

                    .cobro table {
                        width: 97%;
                    }

                    .centreado {
                        text-align: center;
                    }

                    .cobro table tr td {
                        font-size: 0.499em;
                        word-break: break-all;
                    }
                    .cobro table tr td:nth-child(3) {
                        word-break: normal;
                    }

                    .literal {
                        font-size: 0.85em;
                    }

                    .cod_control,
                    .fecha_emision {
                        font-size: 0.7em;
                    }

                    .cobro table {
                        border-collapse: collapse;
                    }

                    .cobro table tr.punteado td {
                        border-top: dashed 1px black;
                        border-bottom: dashed 1px black;
                    }

                    .cobro table tr.punteado_top td {
                        border-top: dashed 1px black;
                    }

                    .qr img {
                        width: 160px;
                        height: 160px;
                    }

                    .total {
                        font-size: 0.9em !important;
                    }

                    .pr-10 {
                        padding-right: 10px;
                        font-size: 8pt !important;
                    }
                </style>
                `
            );
            a.document.write("</head>");
            a.document.write("<body >");
            a.document.write(divContents);
            a.document.write("</body></html>");
            a.document.close();
            a.print();
            a.close();

            setTimeout(() => {
                this.imprimiendo = false;
            }, 300);
        },
    },
};
</script>
<style scoped>
/* FACURA */
.contenedor_factura {
    font-size: 0.95em;
    width: 7cm !important;
    padding-top: 15px;
    padding-bottom: 15px;
    font-family: "Times New Roman", Times, serif;
}

.elemento {
    text-align: center;
}

.elemento.logo img {
    width: 60%;
}

.separador {
    padding: 0px;
    margin: 0px;
}

.fono,
.lp {
    font-size: 0.75em;
}

.txt_fo {
    margin-top: 3px;
    border-top: solid 1px black;
}

.detalle {
    border-top: solid 1px black;
    border-bottom: solid 1px black;
}
.bt {
    border-top: solid 1px black;
}

.act_eco {
    font-size: 0.73em;
}

.info1 {
    text-align: center;
    font-weight: bold;
    font-size: 0.75em;
}

.info2 {
    text-align: center;
    font-weight: bold;
    font-size: 0.7em;
}

.izquierda {
    text-align: left;
    padding-left: 5px;
}

.derecha {
    text-align: right;
    padding-right: 5px;
}

.informacion {
    padding: 5px;
    width: 100%;
}

.bold {
    font-weight: bold;
}

.cobro {
    width: 100%;
    padding: 5px;
}

.cobro table {
    width: 100%;
}

.centreado {
    text-align: center;
}

.cobro table tr td {
    font-size: 0.9em;
}

.literal {
    font-size: 0.7em;
}

.cod_control,
.fecha_emision {
    font-size: 0.9em;
}

.cobro table {
    border-collapse: collapse;
}

.cobro table tr.punteado td {
    border-top: solid 1px black;
    border-bottom: solid 1px black;
}

.qr img {
    width: 160px;
    height: 160px;
}

.total {
    font-size: 0.9em !important;
}
</style>
