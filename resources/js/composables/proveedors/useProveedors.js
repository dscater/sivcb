import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oProveedor = ref({
    id: 0,
    razon_social: "",
    nit: "",
    dir: "",
    fono: "",
    nombre_contacto: "",
    descripcion: "",
    _method: "POST",
});

export const useProveedors = () => {
    const { flash } = usePage().props;
    const getProveedors = async () => {
        try {
            const response = await axios.get(route("proveedors.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.proveedors;
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

    const getProveedorsByTipo = async (data) => {
        try {
            const response = await axios.get(route("proveedors.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.proveedors;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getProveedorsApi = async (data) => {
        try {
            const response = await axios.get(
                route("proveedors.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.proveedors;
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
    const saveProveedor = async (data) => {
        try {
            const response = await axios.post(route("proveedors.store", data), {
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

    const deleteProveedor = async (id) => {
        try {
            const response = await axios.delete(
                route("proveedors.destroy", id),
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

    const setProveedor = (item = null) => {
        if (item) {
            oProveedor.value.id = item.id;
            oProveedor.value.razon_social = item.razon_social;
            oProveedor.value.nit = item.nit;
            oProveedor.value.dir = item.dir;
            oProveedor.value.fono = item.fono;
            oProveedor.value.nombre_contacto = item.nombre_contacto;
            oProveedor.value.descripcion = item.descripcion;
            oProveedor.value._method = "PUT";
            return oProveedor;
        }
        return false;
    };

    const limpiarProveedor = () => {
        oProveedor.value.id = 0;
        oProveedor.value.razon_social = "";
        oProveedor.value.nit = "";
        oProveedor.value.dir = "";
        oProveedor.value.fono = "";
        oProveedor.value.nombre_contacto = "";
        oProveedor.value.descripcion = "";
        oProveedor.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oProveedor,
        getProveedors,
        getProveedorsApi,
        saveProveedor,
        deleteProveedor,
        setProveedor,
        limpiarProveedor,
        getProveedorsByTipo,
    };
};
