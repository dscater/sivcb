import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oDistribucionProducto = reactive({
    id: 0,
    sucursal_id: "",
    sucursal: reactive({ nombre: "", descripcion: "" }),
    producto_barras: reactive([]),
    eliminados: reactive([]),
    _method: "POST",
});

export const useDistribucionProductos = () => {
    const { flash } = usePage().props;
    const getDistribucionProductos = async () => {
        try {
            const response = await axios.get(
                route("distribucion_productos.listado"),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.distribucion_productos;
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

    const getDistribucionProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("distribucion_productos.paginado", data),
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
    const saveDistribucionProducto = async (data) => {
        try {
            const response = await axios.post(
                route("distribucion_productos.store", data),
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

    const deleteDistribucionProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("distribucion_productos.destroy", id),
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

    const setDistribucionProducto = (item = null) => {
        if (item) {
            oDistribucionProducto.id = item.id;
            oDistribucionProducto.sucursal_id = item.sucursal_id;
            oDistribucionProducto.producto_barras = [...item.producto_barras];
            oDistribucionProducto._method = "PUT";
            oDistribucionProducto.sucursal = item.sucursal;
            oDistribucionProducto.eliminados = [];
            return oDistribucionProducto;
        }
        return false;
    };

    const limpiarDistribucionProducto = () => {
        oDistribucionProducto.id = 0;
        oDistribucionProducto.sucursal_id = "";
        oDistribucionProducto.producto_barras = [];
        oDistribucionProducto.eliminados = [];
        oDistribucionProducto._method = "POST";
    };

    onMounted(() => {});

    return {
        oDistribucionProducto,
        getDistribucionProductos,
        getDistribucionProductosApi,
        saveDistribucionProducto,
        deleteDistribucionProducto,
        setDistribucionProducto,
        limpiarDistribucionProducto,
    };
};
