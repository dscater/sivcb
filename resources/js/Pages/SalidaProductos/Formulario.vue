<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useSalidaProductos } from "@/composables/salida_productos/useSalidaProductos";
import { useProductos } from "@/composables/productos/useProductos";
import { useTipoSalidas } from "@/composables/tipo_salidas/useTipoSalidas";
import { useSucursals } from "@/composables/sucursals/useSucursals";
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

const { oSalidaProducto, limpiarSalidaProducto } = useSalidaProductos();
const { getProductos } = useProductos();
const { getTipoSalidas } = useTipoSalidas();
const { getSucursals } = useSucursals();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oSalidaProducto);
let switcheryInstance = null;
const cod_prod_ref = ref(null);
const cod_prod = ref("");
const listProductos = ref([]);
const listTipoSalidas = ref([]);
const listSucursals = ref([]);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarListas();
            await nextTick();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oSalidaProducto);
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

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i>Agregar Registro`
        : `<i class="fa fa-edit"></i>Editar Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("salida_productos.store")
            : route("salida_productos.update", form.id);

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
            limpiarSalidaProducto();
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

const agregarProducto = () => {
    if (form.producto_id && ("" + form.producto_id).trim() != "") {
        if ("" + cod_prod.value.trim() != "") {
            if (verificaCodigo(cod_prod.value) < 0) {
                axios
                    .get(route("producto_barras.getByCod"), {
                        params: { codigo: cod_prod.value },
                    })
                    .then((response) => {
                        if (response.data.producto_id == form.producto_id) {
                            form.producto_barras.push({
                                id: 0,
                                producto_id: 0,
                                codigo: cod_prod.value,
                                lugar: "",
                                sucursal_id: 0,
                                salida_id: 0,
                                salida_id: 0,
                                venta_detalle_id: 0,
                                distribucion_detalle_id: 0,
                            });
                        } else {
                            Swal.fire({
                                icon: "info",
                                title: "Error",
                                text: `El producto que intentas agregar no pertenece al producto seleccionado`,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: `Aceptar`,
                            });
                        }
                    });
            }
            cod_prod.value = "";
            cod_prod_ref.value.focus();
            asignaCantidad();
        }
    } else {
        Swal.fire({
            icon: "info",
            title: "Error",
            text: `Debes seleccionar un producto antes de agregar`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
        cod_prod.value = "";
    }
};

const asignaCantidad = () => {
    form.cantidad = form.producto_barras.length;
};

const eliminaProducto = (index) => {
    if (form.producto_barras[index].id != 0) {
        form.eliminados.push(form.producto_barras[index].id);
    }
    form.producto_barras.splice(index, 1);
    asignaCantidad();
};

const verificaCodigo = (cod) => {
    // Encuentra el índice del elemento cuyo código sea igual a cod
    return form.producto_barras.findIndex(
        (producto) => producto.codigo === cod
    );
};

const cargarListas = () => {
    cargarProductos();
    cargarTipoSalidas();
    cargarSucursals();
};

const cargarProductos = async () => {
    listProductos.value = await getProductos();
};
const cargarTipoSalidas = async () => {
    listTipoSalidas.value = await getTipoSalidas();
};
const cargarSucursals = async () => {
    listSucursals.value = await getSucursals();
};

const customLabels = {
    placeholder: "Selecciona un producto", // Texto del placeholder
    noResults: "No se encontraron resultados", // Texto cuando no hay resultados
};

onMounted(() => {});
</script>

<template>
    <div
        class="modal fade modal_registro"
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
                            <div class="col-12" v-if="accion_dialog == 0">
                                <label>Seleccionar Producto*</label>
                                <multiselect
                                    :class="{
                                        'parsley-error':
                                            form.errors?.producto_id,
                                    }"
                                    v-model="form.producto_id"
                                    :options="listProductos"
                                    track-by="id"
                                    label="label"
                                    selectLabel="Enter para seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="Enter para remover"
                                    :custom-labels="customLabels"
                                    showNoOptions="Sin registros"
                                    noResultsText="Sin resultados"
                                    placeholder="Selecciona un producto"
                                >
                                    <template #noResult="{ option }">
                                        <div>Sin resultados</div>
                                    </template>
                                    <template #noOptions="{ option }">
                                        <div>Sin registros</div>
                                    </template>
                                    <template #singleLabel="{ option }">
                                        <div>{{ option.nombre }}</div>
                                    </template>
                                    <template #option="{ option }">
                                        <div>
                                            {{ option.nombre }} -
                                            {{ option.precio }}
                                        </div>
                                    </template>
                                </multiselect>
                                <ul
                                    v-if="form.errors?.producto_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.producto_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12" v-else>
                                <label>Producto:</label>
                                <input
                                    type="text"
                                    class="form-control readonly"
                                    v-if="form.producto"
                                    v-model="form.producto.nombre"
                                    readonly
                                />
                            </div>
                            <div class="col-12">
                                <label>Cantidad*</label>
                                <input
                                    type="number"
                                    step="1"
                                    class="form-control readonly"
                                    :class="{
                                        'parsley-error': form.errors?.cantidad,
                                    }"
                                    v-model="form.cantidad"
                                    readonly
                                />
                                <ul
                                    v-if="form.errors?.cantidad"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.cantidad }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <label>Tipo de salida*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.tipo_salida_id,
                                    }"
                                    v-model="form.tipo_salida_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listTipoSalidas"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.tipo_salida_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.tipo_salida_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <label>Descripción</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.descripcion,
                                    }"
                                    v-model="form.descripcion"
                                />
                                <ul
                                    v-if="form.errors?.descripcion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.descripcion }}
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

<style scoped>
.multiselect__tags {
  border: 1px solid #ced4da; /* Borde similar a Bootstrap */
  border-radius: 0.25rem; /* Bordes redondeados */
  padding: 0.375rem 0.75rem; /* Padding como en Bootstrap */
  height: auto; /* Asegura altura automática */
}

/* Color del fondo y del texto del elemento seleccionado */
.vue-multiselect__single {
  background-color: #ffffff; /* Color de fondo del seleccionado */
  color: #495057; /* Color del texto del seleccionado */
  border: 1px solid #ced4da; /* Borde */
}

/* Color del fondo de las opciones */
.vue-multiselect__content {
  background-color: #ffffff; /* Color de fondo de las opciones */
}

/* Estilo para la opción seleccionada */
.vue-multiselect__option--highlight {
  background-color: #007bff; /* Color de fondo de la opción seleccionada */
  color: white; /* Color del texto de la opción seleccionada */
}

/* Ajustar el texto de las opciones */
.vue-multiselect__option {
  padding: 0.5rem 1rem; /* Padding para las opciones */
  cursor: pointer; /* Cambia el cursor para indicar que es clickeable */
}

.vue-multiselect__option:hover {
  background-color: #f8f9fa; /* Color al pasar el mouse por encima */
}
</style>
