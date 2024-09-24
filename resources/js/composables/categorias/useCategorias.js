import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oCategoria = ref({
    id: 0,
    nombre: "",
    fono: "",
    dir: "",
    _method: "POST",
});

export const useCategorias = () => {
    const { flash } = usePage().props;
    const getCategorias = async () => {
        try {
            const response = await axios.get(route("categorias.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.categorias;
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

    const getCategoriasByTipo = async (data) => {
        try {
            const response = await axios.get(route("categorias.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.categorias;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getCategoriasApi = async (data) => {
        try {
            const response = await axios.get(route("categorias.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.categorias;
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
    const saveCategoria = async (data) => {
        try {
            const response = await axios.post(route("categorias.store", data), {
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

    const deleteCategoria = async (id) => {
        try {
            const response = await axios.delete(route("categorias.destroy", id), {
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

    const setCategoria = (item = null) => {
        if (item) {
            oCategoria.value.id = item.id;
            oCategoria.value.nombre = item.nombre;
            oCategoria.value.fono = item.fono;
            oCategoria.value.dir = item.dir;
            oCategoria.value._method = "PUT";
            return oCategoria;
        }
        return false;
    };

    const limpiarCategoria = () => {
        oCategoria.value.id = 0;
        oCategoria.value.nombre = "";
        oCategoria.value.fono = "";
        oCategoria.value.dir = "";
        oCategoria.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oCategoria,
        getCategorias,
        getCategoriasApi,
        saveCategoria,
        deleteCategoria,
        setCategoria,
        limpiarCategoria,
        getCategoriasByTipo,
    };
};
