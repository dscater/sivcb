<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Ventas",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, router } from "@inertiajs/vue3";
import { useVentas } from "@/composables/Ventas/useVentas";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
// import { useMenu } from "@/composables/useMenu";
// const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getVentas, setVenta, limpiarVenta, deleteVenta } = useVentas();

const columns = [
    {
        title: "",
        data: "id",
    },
    {
        title: "SUCURSAL",
        data: "sucursal.nombre",
    },
    {
        title: "USUARIO",
        data: "user.usuario",
    },
    {
        title: "CLIENTE",
        data: "cliente.nombre",
    },
    {
        title: "C.I./NIT",
        data: "nit",
    },
    {
        title: "TOTAL",
        data: "total",
    },
    {
        title: "DESCUENTO (1-100%)",
        data: "descuento",
    },
    {
        title: "TOTAL FINAL",
        data: "total_final",
    },
    {
        title: "FECHA",
        data: "fecha_registro_t",
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
                 data-nombre="${row.fecha_registro_t} | ${
                row.sucursal.nombre
            } | ${row.cliente.nombre} | ${row.total_final}" 
                 data-url="${route(
                     "ventas.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
        },
    },
];
const loading = ref(true);

const accionesRow = () => {
    // editar
    $("#table-Venta").on("click", "button.editar", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        router.get(route("ventas.edit", id));
    });
    // eliminar
    $("#table-Venta").on("click", "button.eliminar", function (e) {
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
                let respuesta = await deleteVenta(id);
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
    datatable = initDataTable("#table-Venta", columns, route("ventas.api"));
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
    <Head title="Ventas"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Ventas</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="">
                        <Link
                            class="btn btn-primary"
                            :href="route('ventas.create')"
                        >
                            <i class="fa fa-plus"></i> Nuevo
                        </Link>
                    </h4>
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
                        id="table-Venta"
                        width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap tabla_datos"
                    >
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
