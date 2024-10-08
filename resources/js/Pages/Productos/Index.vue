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
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
// import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
import ProductoBarras from "./ProductoBarras.vue";
// const { mobile, identificaDispositivo } = useMenu();

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getProductos, setProducto, limpiarProducto, deleteProducto } =
    useProductos();

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
        title: "ACCIONES",
        data: null,
        render: function (data, type, row) {
            return `
                <button class="mx-0 rounded-0 btn btn-warning editar" data-id="${
                    row.id
                }"><i class="fa fa-edit"></i></button>
                <button class="mx-0 rounded-0 btn btn-danger eliminar"
                 data-id="${row.id}" 
                 data-nombre="${row.nombre}" 
                 data-url="${route(
                     "productos.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
        },
    },
];

if (user.value.tipo != "ADMINISTRADOR") {
    columns = [
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
            data: "stock_sucursal",
        },
        {
            title: "ACCIONES",
            data: null,
            render: function (data, type, row) {
                let buttons = ``;
                if (user.value.tipo != "ADMINISTRADOR") {
                    buttons += `
                        <button class="mx-0 rounded-0 btn btn-primary codigos" data-id="${row.id}"><i class="fa fa-list-alt"></i></button>
                    `;
                }

                if (user.value.permisos.includes("productos.edit")) {
                    buttons += `<button class="mx-0 rounded-0 btn btn-warning editar" data-id="${row.id}"><i class="fa fa-edit"></i></button>`;
                }
                if (user.value.permisos.includes("productos.destroy")) {
                    buttons += `<button class="mx-0 rounded-0 btn btn-danger eliminar"
                 data-id="${row.id}"
                 data-nombre="${row.nombre}"
                 data-url="${route(
                     "productos.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
                }
                return buttons;
            },
        },
    ];
}

const loading = ref(true);
const accion_dialog = ref(0);
const open_dialog = ref(false);
const accion_dialog_barra = ref(0);
const open_dialog_barra = ref(false);
const listProductosCodigo = ref([]);
const agregarRegistro = () => {
    limpiarProducto();
    accion_dialog.value = 0;
    open_dialog.value = true;
};

const accionesRow = () => {
    // codigos
    $("#table-producto").on("click", "button.codigos", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        if (user.value.tipo != "ADMINISTRADOR") {
            axios
                .get(route("producto_barras.getByProductoSucursalAlmacen"), {
                    params: {
                        producto_id: id,
                        lugar: "SUCURSAL",
                        sucursal_id: user.value.sucursal_id,
                    },
                })
                .then((response) => {
                    listProductosCodigo.value = response.data;
                    open_dialog_barra.value = true;
                });
        }
    });
    // editar
    $("#table-producto").on("click", "button.editar", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        axios.get(route("productos.show", id)).then((response) => {
            setProducto(response.data);
            accion_dialog.value = 1;
            open_dialog.value = true;
        });
    });
    // eliminar
    $("#table-producto").on("click", "button.eliminar", function (e) {
        e.preventDefault();
        let nombre = $(this).attr("data-nombre");
        let id = $(this).attr("data-id");
        Swal.fire({
            title: "¿Quierés eliminar este registro?",
            html: `<strong>${nombre}</strong>`,
            showCancelButton: true,
            confirmButtonColor: "#B61431",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "No, cancelar",
            denyButtonText: `No, cancelar`,
        }).then(async (result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let respuesta = await deleteProducto(id);
                if (respuesta && respuesta.sw) {
                    updateDatatable();
                }
            }
        });
    });
};

var datatable = null;
const datatableInitialized = ref(false);
const updateDatatable = () => {
    datatable.ajax.reload();
};

onMounted(async () => {
    if (user.value.tipo == "ADMINISTRADOR") {
        datatable = initDataTable(
            "#table-producto",
            columns,
            route("productos.api")
        );
    } else {
        datatable = initDataTable(
            "#table-producto",
            columns,
            route("productos.api"),
            {
                rowCallback: function (row, data) {
                    // Cambia el color según el valor de stock_sucursal
                    if (
                        typeof data.stock_sucursal != "undefined" &&
                        typeof data.stock_min != "undefined"
                    ) {
                        if (data.stock_sucursal < data.stock_min) {
                            $(row).addClass("bg-danger");
                        } else {
                            $(row).removeClass("bg-danger");
                        }
                    }
                    if (
                        typeof data.stock_actual != "undefined" &&
                        typeof data.stock_min != "undefined"
                    ) {
                        if (data.stock_actual < data.stock_min) {
                            $(row).addClass("bg-danger");
                        } else {
                            $(row).removeClass("bg-danger");
                        }
                    }
                },
            }
        );
    }

    datatableInitialized.value = true;
    accionesRow();
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
    <h1 class="page-header">Productos</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <button
                        type="button"
                        class="btn btn-primary mr-1 mt-1"
                        @click="agregarRegistro"
                    >
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                    <Link
                        v-if="user.tipo == 'ADMINISTRADOR'"
                        :href="route('productos.stock_productos')"
                        class="btn btn-warning mt-1"
                        ><i class="fa fa-list"></i> Stock productos</Link
                    >
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

    <Formulario
        :open_dialog="open_dialog"
        :accion_dialog="accion_dialog"
        @envio-formulario="updateDatatable"
        @cerrar-dialog="open_dialog = false"
    ></Formulario>

    <ProductoBarras
        :open_dialog="open_dialog_barra"
        :accion_dialog="accion_dialog_barra"
        :listProductosCodigo="listProductosCodigo"
        @envio-formulario="updateDatatable"
        @cerrar-dialog="open_dialog_barra = false"
    ></ProductoBarras>
</template>
