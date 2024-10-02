<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Stock de Productos",
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
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useMarcas } from "@/composables/marcas/useMarcas";
import { useUnidadMedidas } from "@/composables/unidad_medidas/useUnidadMedidas";
import { useSucursals } from "@/composables/sucursals/useSucursals";

const { getCategorias } = useCategorias();
const { getMarcas } = useMarcas();
const { getUnidadMedidas } = useUnidadMedidas();
const { getSucursals } = useSucursals();
const { setLoading } = useApp();

const cargarListas = () => {
    cargarCategorias();
    cargarMarcas();
    cargarUnidadMedidas();
    cargarSucursals();
};

const listLugar = ref(["ALMACÉN", "SUCURSAL"]);
const listCategorias = ref([]);
const listMarcas = ref([]);
const listUnidadMedidas = ref([]);
const listSucursals = ref([]);
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
    lugar: "ALMACÉN",
    categoria_id: "todos",
    marca_id: "todos",
    unidad_medida_id: "todos",
    sucursal_id: "todos",
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_stock_productos", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};
</script>
<template>
    <Head title="Reporte Stock de Productos"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Reportes > Stock de Productos</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Stock de Productos</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
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
                            <div class="col-md-12">
                                <label>Seleccionar ubicación*</label>
                                <select
                                    :hide-details="
                                        form.errors?.lugar ? false : true
                                    "
                                    :error="form.errors?.lugar ? true : false"
                                    :error-messages="
                                        form.errors?.lugar
                                            ? form.errors?.lugar
                                            : ''
                                    "
                                    v-model="form.lugar"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listLugar"
                                        :value="item"
                                    >
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                            <div
                                class="col-12"
                                v-show="form.lugar == 'SUCURSAL'"
                            >
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
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
