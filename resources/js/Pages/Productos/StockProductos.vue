<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Productos",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useProductos } from "@/composables/productos/useProductos";
import { useSucursals } from "@/composables/sucursals/useSucursals";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
// import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
// const { mobile, identificaDispositivo } = useMenu();

const { flash, auth } = usePage().props;
const user = ref(auth.user);

const { setLoading } = useApp();
const { getSucursals } = useSucursals();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getProductos, setProducto, limpiarProducto, deleteProducto } =
    useProductos();

const listSucursals = ref([]);
const optionLugar = ref({
    lugar: "ALMACÉN",
    sucursal_id: listSucursals.value.length > 0 ? listSucursals[0].id : "",
});
let columns = [
    {
        title: "",
        data: "id",
    },
    {
        title: "",
        data: "url_foto",
        render: function (data, type, row) {
            return `<img src="${data}" class="rounded h-30px my-n1 mx-n1"/>`;
        },
    },
    {
        title: "NOMBRE",
        data: "nombre",
    },
    {
        title: "CATEGORÍA",
        data: "categoria.nombre",
    },
    {
        title: "MARCA",
        data: "marca.nombre",
    },
    {
        title: "UNIDAD DE MEDIDA",
        data: "unidad_medida.nombre",
    },
    {
        title: "PRECIO",
        data: "precio",
    },
    {
        title: "STOCK MIN.",
        data: "stock_min",
    },
    {
        title: "STOCK ACTUAL",
        data: "stock_actual",
    },
];

const loading = ref(true);
const accion_dialog = ref(0);
const open_dialog = ref(false);

var datatable = null;
const datatableInitialized = ref(false);
const updateDatatable = () => {
    datatable.ajax.reload();
};
const cargarListas = () => {
    cargaSucursals();
};

const cargaSucursals = async () => {
    listSucursals.value = await getSucursals();
};

const detectaCambioLugar = () => {
    loading.value = true;
    if (user.value.tipo != "ADMINISTRADOR") {
        optionLugar.value.sucursal_id = user.value.sucursal_id;
    }
    let nuevaRuta =
        route("productos.api_stock") +
        "?lugar=" +
        optionLugar.value.lugar +
        "&sucursal_id=" +
        optionLugar.value.sucursal_id;

    datatable.ajax.url(nuevaRuta).load();
    updateDatatable();
    setTimeout(() => {
        loading.value = false;
    }, 200);
};

onMounted(async () => {
    cargarListas();
    let ruta =
        route("productos.api_stock") +
        "?lugar=" +
        optionLugar.value.lugar +
        "&sucursal_id=" +
        optionLugar.value.sucursal_id;
    datatable = initDataTable("#table-producto", columns, ruta);
    datatableInitialized.value = true;
});
onBeforeUnmount(() => {
    if (datatable) {
        datatable.clear();
        datatable.destroy(false); // Destruye la instancia del DataTable
        datatable = null;
        datatableInitialized.value = false;
    }
});
</script>
<template>
    <Head title="Productos"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Productos > Stock de Productos</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <Link
                        :href="route('productos.index')"
                        class="btn btn-outline-info mx-1"
                    >
                        <i class="fa fa-arrow-left"></i> Volver
                    </Link>
                    <select
                        v-model="optionLugar.lugar"
                        @change="detectaCambioLugar"
                    >
                        <option value="ALMACÉN">ALMACÉN</option>
                        <option value="SUCURSAL">SUCURSAL</option>
                    </select>
                    <select
                        v-if="optionLugar.lugar == 'SUCURSAL' && user.tipo == 'ADMINISTRADOR'"
                        v-model="optionLugar.sucursal_id"
                        @change="detectaCambioLugar"
                    >
                        <option v-for="item in listSucursals" :value="item.id">
                            {{ item.nombre }}
                        </option>
                    </select>
                    <panel-toolbar
                        style="margin-left: auto"
                        :mostrar_loading="loading"
                        @loading="updateDatatable"
                    />
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <table
                        id="table-producto"
                        width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap tabla_datos"
                    >
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
