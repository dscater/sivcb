import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oMarca = ref({
    id: 0,
    nombre: "",
    _method: "POST",
});

export const useMarcas = () => {
    const { flash } = usePage().props;
    const getMarcas = async () => {
        try {
            const response = await axios.get(route("marcas.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.marcas;
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

    const getMarcasByTipo = async (data) => {
        try {
            const response = await axios.get(route("marcas.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.marcas;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getMarcasApi = async (data) => {
        try {
            const response = await axios.get(route("marcas.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.marcas;
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
    const saveMarca = async (data) => {
        try {
            const response = await axios.post(route("marcas.store", data), {
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

    const deleteMarca = async (id) => {
        try {
            const response = await axios.delete(route("marcas.destroy", id), {
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

    const setMarca = (item = null) => {
        if (item) {
            oMarca.value.id = item.id;
            oMarca.value.nombre = item.nombre;
            oMarca.value._method = "PUT";
            return oMarca;
        }
        return false;
    };

    const limpiarMarca = () => {
        oMarca.value.id = 0;
        oMarca.value.nombre = "";
        oMarca.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oMarca,
        getMarcas,
        getMarcasApi,
        saveMarca,
        deleteMarca,
        setMarca,
        limpiarMarca,
        getMarcasByTipo,
    };
};
