import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oVenta = reactive({
    id: 0,
    sucursal_id: "",
    cliente_id: "",
    user_id: "",
    nit: "",
    total: "",
    descuento: 0,
    total_final: "",
    producto_barras: reactive([]),
    venta_detalles: reactive([]),
    eliminados: reactive([]),
    _method: "POST",
});

export const useVentas = () => {
    const { flash } = usePage().props;
    const getVentas = async () => {
        try {
            const response = await axios.get(route("ventas.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.ventas;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getVentasByTipo = async (data) => {
        try {
            const response = await axios.get(route("ventas.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.tipo_ingresos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getVentasApi = async (data) => {
        try {
            const response = await axios.get(route("ventas.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.tipo_ingresos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const saveVenta = async (data) => {
        try {
            const response = await axios.post(route("ventas.store", data), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteVenta = async (id) => {
        try {
            const response = await axios.delete(route("ventas.destroy", id), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setVenta = (item = null) => {
        if (item) {
            oVenta.id = item.id;
            oVenta.sucursal_id = item.sucursal_id;
            oVenta.cliente_id = item.cliente_id;
            oVenta.user_id = item.user_id;
            oVenta.nit = item.nit;
            oVenta.total = item.total;
            oVenta.descuento = item.descuento;
            oVenta.total_final = item.total_final;
            oVenta.producto_barras = [...item.producto_barras];
            oVenta.venta_detalles = [...item.venta_detalles];
            oVenta._method = "PUT";
            oVenta.producto = item.producto ? item.producto : null;
            oVenta.sucursal = item.sucursal ? item.sucursal : null;
            oVenta.eliminados = [];
            return oVenta;
        }
        return false;
    };

    const limpiarVenta = () => {
        oVenta.id = 0;
        oVenta.sucursal_id = "";
        oVenta.cliente_id = "";
        oVenta.user_id = "";
        oVenta.nit = "";
        oVenta.total = "";
        oVenta.descuento = 0;
        oVenta.total_final = "";
        oVenta.producto_barras = [];
        oVenta.venta_detalles = [];
        oVenta.eliminados = [];
        oVenta._method = "POST";
    };

    onMounted(() => {});

    return {
        oVenta,
        getVentas,
        getVentasApi,
        saveVenta,
        deleteVenta,
        setVenta,
        limpiarVenta,
        getVentasByTipo,
    };
};
