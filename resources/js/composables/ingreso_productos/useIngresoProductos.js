import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oIngresoProducto = reactive({
    id: 0,
    producto_id: "",
    proveedor_id: "",
    precio: "",
    cantidad: "",
    tipo_ingreso_id: "",
    descripcion: "",
    lugar: "",
    sucursal_id: "",
    fecha_ingreso: "",
    producto_barras: reactive([]),
    eliminados: reactive([]),
    _method: "POST",
});

export const useIngresoProductos = () => {
    const { flash } = usePage().props;
    const getIngresoProductos = async () => {
        try {
            const response = await axios.get(route("tipo_ingresos.listado"), {
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

    const getIngresoProductosByTipo = async (data) => {
        try {
            const response = await axios.get(route("tipo_ingresos.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.tipo_ingresos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getIngresoProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("tipo_ingresos.paginado", data),
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
    const saveIngresoProducto = async (data) => {
        try {
            const response = await axios.post(
                route("tipo_ingresos.store", data),
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

    const deleteIngresoProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("tipo_ingresos.destroy", id),
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

    const setIngresoProducto = (item = null) => {
        if (item) {
            oIngresoProducto.id = item.id;
            oIngresoProducto.producto_id = item.producto_id;
            oIngresoProducto.proveedor_id = item.proveedor_id;
            oIngresoProducto.precio = item.precio;
            oIngresoProducto.cantidad = item.cantidad;
            oIngresoProducto.tipo_ingreso_id = item.tipo_ingreso_id;
            oIngresoProducto.descripcion = item.descripcion;
            oIngresoProducto.lugar = item.lugar;
            oIngresoProducto.sucursal_id = item.sucursal_id;
            oIngresoProducto.fecha_ingreso = item.fecha_ingreso;
            oIngresoProducto.producto_barras = [...item.producto_barras];
            oIngresoProducto._method = "PUT";
            oIngresoProducto.eliminados = [];
            return oIngresoProducto;
        }
        return false;
    };

    const limpiarIngresoProducto = () => {
        oIngresoProducto.id = 0;
        oIngresoProducto.producto_id = "";
        oIngresoProducto.proveedor_id = "";
        oIngresoProducto.precio = "";
        oIngresoProducto.cantidad = "";
        oIngresoProducto.tipo_ingreso_id = "";
        oIngresoProducto.descripcion = "";
        oIngresoProducto.lugar = "";
        oIngresoProducto.sucursal_id = "";
        oIngresoProducto.fecha_ingreso = "";
        oIngresoProducto.producto_barras = [];
        oIngresoProducto.eliminados = [];
        oIngresoProducto._method = "POST";
    };

    onMounted(() => {});

    return {
        oIngresoProducto,
        getIngresoProductos,
        getIngresoProductosApi,
        saveIngresoProducto,
        deleteIngresoProducto,
        setIngresoProducto,
        limpiarIngresoProducto,
        getIngresoProductosByTipo,
    };
};
