<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Ventas de Lotes de Terrenos",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useUrbanizacions } from "@/composables/urbanizacions/useUrbanizacions";
const { getUrbanizacions } = useUrbanizacions();
const { setLoading } = useApp();
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";

exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const listUrbanizacions = ref([]);

const form = ref({
    urbanizacion_id: "todos",
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte Gráfico";
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte PDF";
});

const generarReporte = () => {
    generando.value = true;
    axios
        .get(route("reportes.r_g_venta_lotes"), {
            params: {
                urbanizacion_id: form.value.urbanizacion_id,
            },
        })
        .then((response) => {
            generando.value = false;
            Highcharts.chart("container", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "LOTES DE TERRENOS",
                },
                subtitle: {
                    align: "left",
                    text: "",
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    },
                },
                xAxis: {
                    categories: response.data.categories,
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                if (this.series.name === "Total") {
                                    return Highcharts.numberFormat(
                                        this.y,
                                        2,
                                        ".",
                                        ","
                                    );
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    },
                },
                series: response.data.series,
            });
        });
};

const generarReportePDF = () => {
    generando.value = true;
    const url = route("reportes.r_pdf_venta_lotes", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const cargarListas = async () => {
    listUrbanizacions.value = await getUrbanizacions();
    listUrbanizacions.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Ventas de Lotes de Terrenos"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">
            Reportes > Ventas de Lotes de Terrenos
        </li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Ventas de Lotes de Terrenos</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <label>Seleccionar Urbanización*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.urbanizacion_id,
                                    }"
                                    v-model="form.urbanizacion_id"
                                    @change="getManzanosByUrbanizacion($event)"
                                >
                                    <option
                                        v-for="item in listUrbanizacions"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.urbanizacion_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.urbanizacion_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary mx-1"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                                <button
                                    class="btn btn-primary mx-1"
                                    block
                                    @click="generarReportePDF"
                                    :disabled="generando"
                                    v-text="txtBtn2"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2" id="container"></div>
    </div>
</template>
