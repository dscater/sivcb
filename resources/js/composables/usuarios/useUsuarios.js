import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oUsuario = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    dir: "",
    email: "",
    fono: "",
    tipo: "",
    foto: "",
    acceso: 0 + "",
    _method: "POST",
});

export const useUsuarios = () => {
    const { flash } = usePage().props;
    const getUsuarios = async () => {
        try {
            const response = await axios.get(route("usuarios.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.usuarios;
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

    const getUsuariosByTipo = async (data) => {
        try {
            const response = await axios.get(route("usuarios.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.usuarios;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getUsuariosApi = async (data) => {
        try {
            const response = await axios.get(route("usuarios.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.usuarios;
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
    const saveUsuario = async (data) => {
        try {
            const response = await axios.post(route("usuarios.store", data), {
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

    const deleteUsuario = async (id) => {
        try {
            const response = await axios.delete(route("usuarios.destroy", id), {
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

    const setUsuario = (item = null) => {
        if (item) {
            oUsuario.value.id = item.id;
            oUsuario.value.nombre = item.nombre;
            oUsuario.value.paterno = item.paterno;
            oUsuario.value.materno = item.materno;
            oUsuario.value.ci = item.ci;
            oUsuario.value.ci_exp = item.ci_exp;
            oUsuario.value.dir = item.dir;
            oUsuario.value.email = item.email;
            oUsuario.value.fono = item.fono;
            oUsuario.value.tipo = item.tipo;
            oUsuario.value.foto = item.foto;
            oUsuario.value.acceso = item.acceso + "";
            oUsuario.value._method = "PUT";
            return oUsuario;
        }
        return false;
    };

    const limpiarUsuario = () => {
        oUsuario.value.id = 0;
        oUsuario.value.nombre = "";
        oUsuario.value.paterno = "";
        oUsuario.value.materno = "";
        oUsuario.value.ci = "";
        oUsuario.value.ci_exp = "";
        oUsuario.value.dir = "";
        oUsuario.value.email = "";
        oUsuario.value.fono = "";
        oUsuario.value.tipo = "";
        oUsuario.value.foto = "";
        oUsuario.value.acceso = 0 + "";
        oUsuario.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oUsuario,
        getUsuarios,
        getUsuariosApi,
        saveUsuario,
        deleteUsuario,
        setUsuario,
        limpiarUsuario,
        getUsuariosByTipo,
    };
};
