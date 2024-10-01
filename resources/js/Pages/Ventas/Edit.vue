<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Venta",
        disabled: false,
        url: route("ventas.index"),
        name_url: "ventas.index",
    },
    {
        title: "Nuevo",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import { useVentas } from "@/composables/ventas/useVentas";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { oVenta, setVenta } = useVentas();

const props = defineProps({
    venta: {
        type: Object,
    },
});
console.log(props.venta)
setVenta(props.venta);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Ventas"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item">
            <Link :href="route('ventas.index')">Ventas</Link>
        </li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Ventas <small>Editar</small></h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <Formulario></Formulario>
        </div>
    </div>
</template>
