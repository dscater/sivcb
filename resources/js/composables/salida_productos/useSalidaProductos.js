import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oSalidaProducto = reactive({
    id: 0,
    producto_id: "",
    cantidad: "",
    fecha_salida: "",
    tipo_salida_id: "",
    descripcion: "",
    _method: "POST",
});

export const useSalidaProductos = () => {
    const { flash } = usePage().props;
    const getSalidaProductos = async () => {
        try {
            const response = await axios.get(
                route("salida_productos.listado"),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.salida_productos;
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

    const getSalidaProductosByTipo = async (data) => {
        try {
            const response = await axios.get(route("salida_productos.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.tipo_ingresos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getSalidaProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("salida_productos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
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
    const saveSalidaProducto = async (data) => {
        try {
            const response = await axios.post(
                route("salida_productos.store", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
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

    const deleteSalidaProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("salida_productos.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
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

    const setSalidaProducto = (item = null) => {
        if (item) {
            oSalidaProducto.id = item.id;
            oSalidaProducto.producto_id = item.producto_id;
            oSalidaProducto.cantidad = item.cantidad;
            oSalidaProducto.fecha_salida = item.fecha_salida;
            oSalidaProducto.tipo_salida_id = item.tipo_salida_id;
            oSalidaProducto.descripcion = item.descripcion;
            oSalidaProducto._method = "PUT";
            oSalidaProducto.producto = item.producto ? item.producto : null;
            oSalidaProducto.sucursal = item.sucursal ? item.sucursal : null;
            return oSalidaProducto;
        }
        return false;
    };

    const limpiarSalidaProducto = () => {
        oSalidaProducto.id = 0;
        oSalidaProducto.producto_id = "";
        oSalidaProducto.cantidad = "";
        oSalidaProducto.fecha_salida = "";
        oSalidaProducto.tipo_salida_id = "";
        oSalidaProducto.descripcion = "";
        oSalidaProducto._method = "POST";
    };

    onMounted(() => {});

    return {
        oSalidaProducto,
        getSalidaProductos,
        getSalidaProductosApi,
        saveSalidaProducto,
        deleteSalidaProducto,
        setSalidaProducto,
        limpiarSalidaProducto,
        getSalidaProductosByTipo,
    };
};
