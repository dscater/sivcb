import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oTipoSalida = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    _method: "POST",
});

export const useTipoSalidas = () => {
    const { flash } = usePage().props;
    const getTipoSalidas = async () => {
        try {
            const response = await axios.get(route("tipo_salidas.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.tipo_salidas;
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

    const getTipoSalidasByTipo = async (data) => {
        try {
            const response = await axios.get(route("tipo_salidas.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.tipo_salidas;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getTipoSalidasApi = async (data) => {
        try {
            const response = await axios.get(
                route("tipo_salidas.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.tipo_salidas;
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
    const saveTipoSalida = async (data) => {
        try {
            const response = await axios.post(route("tipo_salidas.store", data), {
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

    const deleteTipoSalida = async (id) => {
        try {
            const response = await axios.delete(
                route("tipo_salidas.destroy", id),
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

    const setTipoSalida = (item = null) => {
        if (item) {
            oTipoSalida.value.id = item.id;
            oTipoSalida.value.nombre = item.nombre;
            oTipoSalida.value.descripcion = item.descripcion;
            oTipoSalida.value._method = "PUT";
            return oTipoSalida;
        }
        return false;
    };

    const limpiarTipoSalida = () => {
        oTipoSalida.value.id = 0;
        oTipoSalida.value.nombre = "";
        oTipoSalida.value.descripcion = "";
        oTipoSalida.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oTipoSalida,
        getTipoSalidas,
        getTipoSalidasApi,
        saveTipoSalida,
        deleteTipoSalida,
        setTipoSalida,
        limpiarTipoSalida,
        getTipoSalidasByTipo,
    };
};
