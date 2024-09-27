<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useIngresoProductos } from "@/composables/ingreso_productos/useIngresoProductos";
import { useProductos } from "@/composables/productos/useProductos";
import { useProveedors } from "@/composables/proveedors/useProveedors";
import { useTipoIngresos } from "@/composables/tipo_ingresos/useTipoIngresos";
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

const { oIngresoProducto, limpiarIngresoProducto } = useIngresoProductos();
const { getProductos } = useProductos();
const { getProveedors } = useProveedors();
const { getTipoIngresos } = useTipoIngresos();
const { getSucursals } = useSucursals();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oIngresoProducto);
let switcheryInstance = null;
const cod_prod_ref = ref(null);
const cod_prod = ref("");
const listProductos = ref([]);
const listProveedors = ref([]);
const listTipoIngresos = ref([]);
const listSucursals = ref([]);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            console.log(oIngresoProducto);
            cargarListas();
            await nextTick();
            cod_prod_ref.value.focus();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oIngresoProducto);
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
            ? route("ingreso_productos.store")
            : route("ingreso_productos.update", form.id);

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
            limpiarIngresoProducto();
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
    if ("" + cod_prod.value.trim() != "") {
        if (verificaCodigo(cod_prod.value) < 0) {
            form.producto_barras.push({
                id: 0,
                producto_id: 0,
                codigo: cod_prod.value,
                lugar: "",
                sucursal_id: 0,
                ingreso_id: 0,
                salida_id: 0,
                venta_detalle_id: 0,
                distribucion_detalle_id: 0,
            });
        }
        cod_prod.value = "";
        cod_prod_ref.value.focus();
        asignaCantidad();
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
    cargarProveedors();
    cargarTipoIngresos();
    cargarSucursals();
};

const cargarProductos = async () => {
    listProductos.value = await getProductos();
};
const cargarProveedors = async () => {
    listProveedors.value = await getProveedors();
};
const cargarTipoIngresos = async () => {
    listTipoIngresos.value = await getTipoIngresos();
};
const cargarSucursals = async () => {
    listSucursals.value = await getSucursals();
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
        <div class="modal-dialog">
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
                            <div class="col-md-8 border p-2">
                                <div class="row">
                                    <div
                                        class="col-12"
                                        v-if="accion_dialog == 0"
                                    >
                                        <label>Seleccionar Producto*</label>
                                        <select
                                            class="form-select"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.producto_id,
                                            }"
                                            v-model="form.producto_id"
                                        >
                                            <option value="">
                                                - Seleccione -
                                            </option>
                                            <option
                                                v-for="item in listProductos"
                                                :value="item.id"
                                            >
                                                {{ item.nombre }}
                                            </option>
                                        </select>
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
                                        <label>Seleccionar Proveedor*</label>
                                        <select
                                            class="form-select"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.proveedor_id,
                                            }"
                                            v-model="form.proveedor_id"
                                        >
                                            <option value="">
                                                - Seleccione -
                                            </option>
                                            <option
                                                v-for="item in listProveedors"
                                                :value="item.id"
                                            >
                                                {{ item.razon_social }}
                                            </option>
                                        </select>
                                        <ul
                                            v-if="form.errors?.proveedor_id"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{ form.errors?.proveedor_id }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <label>Precio de compra*</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            class="form-control"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.precio,
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
                                    <div class="col-12">
                                        <label>Cantidad*</label>
                                        <input
                                            type="number"
                                            step="1"
                                            class="form-control readonly"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.cantidad,
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
                                        <label>Tipo de ingreso*</label>
                                        <select
                                            class="form-select"
                                            :class="{
                                                'parsley-error':
                                                    form.errors
                                                        ?.tipo_ingreso_id,
                                            }"
                                            v-model="form.tipo_ingreso_id"
                                        >
                                            <option value="">
                                                - Seleccione -
                                            </option>
                                            <option
                                                v-for="item in listTipoIngresos"
                                                :value="item.id"
                                            >
                                                {{ item.nombre }}
                                            </option>
                                        </select>
                                        <ul
                                            v-if="form.errors?.tipo_ingreso_id"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{
                                                    form.errors?.tipo_ingreso_id
                                                }}
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
                                    <div
                                        class="col-12"
                                        v-if="accion_dialog == 0"
                                    >
                                        <label>Recepción de productos*</label>
                                        <select
                                            class="form-select"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.lugar,
                                            }"
                                            v-model="form.lugar"
                                        >
                                            <option value="">
                                                - Seleccione -
                                            </option>
                                            <option value="ALMACÉN">
                                                ALMACÉN
                                            </option>
                                            <option value="SUCURSAL">
                                                SUCURSAL
                                            </option>
                                        </select>
                                        <ul
                                            v-if="form.errors?.lugar"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{ form.errors?.lugar }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12" v-else>
                                        <label>Lugar:</label>
                                        <input
                                            type="text"
                                            class="form-control readonly"
                                            v-model="form.lugar"
                                            readonly
                                        />
                                    </div>
                                    <div
                                        class="col-12"
                                        v-show="form.lugar == 'SUCURSAL'"
                                    >
                                        <template v-if="accion_dialog == 0">
                                            <label>Seleccionar Sucursal*</label>
                                            <select
                                                class="form-select"
                                                :class="{
                                                    'parsley-error':
                                                        form.errors
                                                            ?.sucursal_id,
                                                }"
                                                v-model="form.sucursal_id"
                                            >
                                                <option value="">
                                                    - Seleccione -
                                                </option>
                                                <option
                                                    v-for="item in listSucursals"
                                                    :value="item.id"
                                                >
                                                    {{ item.nombre }}
                                                </option>
                                            </select>
                                            <ul
                                                v-if="form.errors?.sucursal_id"
                                                class="parsley-errors-list filled"
                                            >
                                                <li class="parsley-required">
                                                    {{
                                                        form.errors?.sucursal_id
                                                    }}
                                                </li>
                                            </ul>
                                        </template>
                                        <template v-else>
                                            <label>Sucursal:</label>
                                            <input
                                                type="text"
                                                class="form-control readonly"
                                                v-if="form.sucursal"
                                                v-model="form.sucursal.nombre"
                                                readonly
                                            />
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 border p-2">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Productos agregados</h4>
                                        <input
                                            type="text"
                                            class="form-control"
                                            ref="cod_prod_ref"
                                            v-model="cod_prod"
                                            @keypress.enter.prevent="
                                                agregarProducto()
                                            "
                                        />
                                        <small
                                            class="fs-12px text-gray-500-darker"
                                            >Mantener el campo de texto
                                            seleccionado para agregar los
                                            productos</small
                                        >
                                    </div>
                                    <div class="col-12 lista_productos_codigo">
                                        <table class="table table-panel mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="3%"></th>
                                                    <th>Código</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(
                                                        item, index_producto
                                                    ) in form.producto_barras"
                                                >
                                                    <td class="align-middle">
                                                        {{ index_producto + 1 }}
                                                    </td>
                                                    <td class="align-middle">
                                                        {{ item.codigo }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <button
                                                            class="btn btn-sm btn-danger w-100px"
                                                            @click.prevent="
                                                                eliminaProducto(
                                                                    index_producto
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-trash"
                                                            ></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr
                                                    v-show="
                                                        form.producto_barras
                                                            .length == 0
                                                    "
                                                >
                                                    <td
                                                        colspan="2"
                                                        class="text-center text-gray-300-darker"
                                                    >
                                                        Sin productos
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <ul
                                            v-if="form.errors?.producto_barras"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{
                                                    form.errors?.producto_barras
                                                }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
.lista_productos_codigo {
    max-height: 70vh;
    overflow: auto;
}
</style>
