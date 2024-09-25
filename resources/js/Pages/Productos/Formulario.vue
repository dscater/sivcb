<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useProductos } from "@/composables/productos/useProductos";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useMarcas } from "@/composables/marcas/useMarcas";
import { useUnidadMedidas } from "@/composables/unidad_medidas/useUnidadMedidas";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
const props = defineProps({
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});

const { oProducto, limpiarProducto } = useProductos();
const { getCategorias } = useCategorias();
const { getMarcas } = useMarcas();
const { getUnidadMedidas } = useUnidadMedidas();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oProducto.value);
let switcheryInstance = null;
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            const accesoCheckbox = $("#acceso");
            if (oProducto.value.acceso == 1) {
                accesoCheckbox.prop("checked", false).trigger("click");
            } else {
                accesoCheckbox.prop("checked", true).trigger("click");
            }

            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oProducto.value);
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const { flash } = usePage().props;

const listTipos = ["ADMINISTRADOR", "SUPERVISOR DE SUCURSAL", "OPERADOR"];
const listCategorias = ref([]);
const listMarcas = ref([]);
const listUnidadMedidas = ref([]);

const imagen = ref(null);

function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i>Agregar Registro`
        : `<i class="fa fa-edit"></i>Editar Registro`;
});

const initializeSwitcher = () => {
    const accesoCheckbox = document.getElementById("acceso");
    if (accesoCheckbox) {
        // Destruye la instancia previa si existe
        // Inicializa Switchery
        switcheryInstance = new Switchery(accesoCheckbox, {
            color: "#0078ff",
        });
    }
};
const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("productos.store")
            : route("productos.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            dialog.value = false;
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarProducto();
            emits("envio-formulario");
        },
        onError: (err) => {
            console.log("ERROR");
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};

const cargarListas = () => {
    cargarCategorias();
    cargarMarcas();
    cargarUnidadMedidas();
};

const cargarCategorias = async () => {
    listCategorias.value = await getCategorias();
};

const cargarMarcas = async () => {
    listMarcas.value = await getMarcas();
};

const cargarUnidadMedidas = async () => {
    listUnidadMedidas.value = await getUnidadMedidas();
};

onMounted(() => {
    cargarListas();
    initializeSwitcher();
});
</script>

<template>
    <div
        class="modal fade"
        :class="{
            show: dialog,
        }"
        id="modal-dialog-form"
        :style="{
            display: dialog ? 'block' : 'none',
        }"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" v-html="tituloDialog"></h4>
                    <button
                        type="button"
                        class="btn-close"
                        @click="cerrarDialog()"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="enviarFormulario()">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Nombre Producto*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.nombre,
                                    }"
                                    v-model="form.nombre"
                                />
                                <ul
                                    v-if="form.errors?.nombre"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nombre }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Seleccionar Categoría*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.categoria_id,
                                    }"
                                    v-model="form.categoria_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listCategorias"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>

                                <ul
                                    v-if="form.errors?.categoria_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.categoria_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Seleccionar Marca*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error': form.errors?.marca_id,
                                    }"
                                    v-model="form.marca_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listMarcas"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>

                                <ul
                                    v-if="form.errors?.marca_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.marca_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Seleccionar Unidad de Medida*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.unidad_medida_id,
                                    }"
                                    v-model="form.unidad_medida_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listUnidadMedidas"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>

                                <ul
                                    v-if="form.errors?.unidad_medida_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.unidad_medida_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Precio de venta*</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.precio,
                                    }"
                                    v-model="form.precio"
                                />

                                <ul
                                    v-if="form.errors?.precio"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.precio }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Stock mínimo*</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.stock_min,
                                    }"
                                    v-model="form.stock_min"
                                />

                                <ul
                                    v-if="form.errors?.stock_min"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.stock_min }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Imagen referencial</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.imagen,
                                    }"
                                    ref="imagen"
                                    @change="cargaArchivo($event, 'imagen')"
                                />

                                <ul
                                    v-if="form.errors?.imagen"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.imagen }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a
                        href="javascript:;"
                        class="btn btn-white"
                        @click="cerrarDialog()"
                        ><i class="fa fa-times"></i> Cerrar</a
                    >
                    <button
                        type="button"
                        @click="enviarFormulario()"
                        class="btn btn-primary"
                    >
                        <i class="fa fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
