import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oSucursal = ref({
    id: 0,
    nombre: "",
    fono: "",
    dir: "",
    _method: "POST",
});

export const useSucursals = () => {
    const { flash } = usePage().props;
    const getSucursals = async () => {
        try {
            const response = await axios.get(route("sucursals.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.sucursals;
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

    const getSucursalsByTipo = async (data) => {
        try {
            const response = await axios.get(route("sucursals.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.sucursals;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getSucursalsApi = async (data) => {
        try {
            const response = await axios.get(route("sucursals.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.sucursals;
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
    const saveSucursal = async (data) => {
        try {
            const response = await axios.post(route("sucursals.store", data), {
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

    const deleteSucursal = async (id) => {
        try {
            const response = await axios.delete(route("sucursals.destroy", id), {
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

    const setSucursal = (item = null) => {
        if (item) {
            oSucursal.value.id = item.id;
            oSucursal.value.nombre = item.nombre;
            oSucursal.value.fono = item.fono;
            oSucursal.value.dir = item.dir;
            oSucursal.value._method = "PUT";
            return oSucursal;
        }
        return false;
    };

    const limpiarSucursal = () => {
        oSucursal.value.id = 0;
        oSucursal.value.nombre = "";
        oSucursal.value.fono = "";
        oSucursal.value.dir = "";
        oSucursal.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oSucursal,
        getSucursals,
        getSucursalsApi,
        saveSucursal,
        deleteSucursal,
        setSucursal,
        limpiarSucursal,
        getSucursalsByTipo,
    };
};
