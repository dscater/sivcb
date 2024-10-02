<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Ventas",
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
import { useProductos } from "@/composables/productos/useProductos";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useMarcas } from "@/composables/marcas/useMarcas";
import { useUnidadMedidas } from "@/composables/unidad_medidas/useUnidadMedidas";
import { useSucursals } from "@/composables/sucursals/useSucursals";
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

const { auth } = usePage().props;
const user = ref(auth.user);
const { getProductos } = useProductos();
const { getCategorias } = useCategorias();
const { getMarcas } = useMarcas();
const { getUnidadMedidas } = useUnidadMedidas();
const { getSucursals } = useSucursals();
const { setLoading } = useApp();

const cargarListas = () => {
    cargarProductos();
    cargarCategorias();
    cargarMarcas();
    cargarUnidadMedidas();
    cargarSucursals();
};

const listProductos = ref([]);
const listCategorias = ref([]);
const listMarcas = ref([]);
const listUnidadMedidas = ref([]);
const listSucursals = ref([]);

const cargarProductos = async () => {
    listProductos.value = await getProductos();
    listProductos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};
const cargarCategorias = async () => {
    listCategorias.value = await getCategorias();
    listCategorias.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};
const cargarMarcas = async () => {
    listMarcas.value = await getMarcas();
    listMarcas.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};
const cargarUnidadMedidas = async () => {
    listUnidadMedidas.value = await getUnidadMedidas();
    listUnidadMedidas.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};
const cargarSucursals = async () => {
    listSucursals.value = await getSucursals();
    listSucursals.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};

onMounted(() => {
    cargarListas();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    producto_id: "todos",
    categoria_id: "todos",
    marca_id: "todos",
    unidad_medida_id: "todos",
    sucursal_id:
        user.value.tipo == "ADMINISTRADOR" ? "todos" : user.value.sucursal_id,
    fecha_ini: "",
    fecha_fin: "",
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte PDF";
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte Gráfico";
});

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_ventas", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const generarReporteG = () => {
    generando.value = true;
    axios
        .get(route("reportes.g_ventas"), {
            params: form.value,
        })
        .then((response) => {
            generando.value = false;
            Highcharts.chart("container", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "VENTAS",
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
</script>
<template>
    <Head title="Reporte Ventas"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Reportes > Ventas</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Ventas</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Seleccionar producto*</label>
                                <select
                                    :hide-details="
                                        form.errors?.producto_id ? false : true
                                    "
                                    :error="
                                        form.errors?.producto_id ? true : false
                                    "
                                    :error-messages="
                                        form.errors?.producto_id
                                            ? form.errors?.producto_id
                                            : ''
                                    "
                                    v-model="form.producto_id"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listProductos"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Seleccionar Categoría*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.categoria_id,
                                    }"
                                    v-model="form.categoria_id"
                                >
                                    <option
                                        v-for="item in listCategorias"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Seleccionar Marca*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error': form.errors?.marca_id,
                                    }"
                                    v-model="form.marca_id"
                                >
                                    <option
                                        v-for="item in listMarcas"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Seleccionar Unidad de medida*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.unidad_medida_id,
                                    }"
                                    v-model="form.unidad_medida_id"
                                >
                                    <option
                                        v-for="item in listUnidadMedidas"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12" v-if="user.tipo == 'ADMINISTRADOR'">
                                <label>Seleccionar Sucursal*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.sucursal_id,
                                    }"
                                    v-model="form.sucursal_id"
                                >
                                    <option
                                        v-for="item in listSucursals"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Fecha Inicio</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.fecha_ini,
                                            }"
                                            v-model="form.fecha_ini"
                                        />
                                        <ul
                                            v-if="form.errors?.fecha_ini"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{ form.errors?.fecha_ini }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Fecha Fin</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.fecha_fin,
                                            }"
                                            v-model="form.fecha_fin"
                                        />
                                        <ul
                                            v-if="form.errors?.fecha_fin"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{ form.errors?.fecha_fin }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary mx-1 mt-1"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                                <button
                                    class="btn btn-info mx-1 mt-1"
                                    block
                                    @click="generarReporteG"
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
