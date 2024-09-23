import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oUrbanizacion = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    _method: "POST",
});

export const useUrbanizacions = () => {
    const { flash } = usePage().props;
    const getUrbanizacions = async () => {
        try {
            const response = await axios.get(route("urbanizacions.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.urbanizacions;
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

    const getUrbanizacionsApi = async (data) => {
        try {
            const response = await axios.get(
                route("urbanizacions.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.urbanizacions;
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
    const saveUrbanizacion = async (data) => {
        try {
            const response = await axios.post(
                route("urbanizacions.store", data),
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

    const deleteUrbanizacion = async (id) => {
        try {
            const response = await axios.delete(
                route("urbanizacions.destroy", id),
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

    const setUrbanizacion = (item = null) => {
        if (item) {
            oUrbanizacion.value.id = item.id;
            oUrbanizacion.value.nombre = item.nombre;
            oUrbanizacion.value.descripcion = item.descripcion;
            oUrbanizacion.value.acceso = item.acceso + "";
            oUrbanizacion.value._method = "PUT";
            return oUrbanizacion;
        }
        return false;
    };

    const limpiarUrbanizacion = () => {
        oUrbanizacion.value.id = 0;
        oUrbanizacion.value.nombre = "";
        oUrbanizacion.value.descripcion = "";
        oUrbanizacion.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oUrbanizacion,
        getUrbanizacions,
        getUrbanizacionsApi,
        saveUrbanizacion,
        deleteUrbanizacion,
        setUrbanizacion,
        limpiarUrbanizacion,
    };
};
