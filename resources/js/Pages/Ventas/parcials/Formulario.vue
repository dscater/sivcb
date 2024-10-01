<script setup>
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { useVentas } from "@/composables/ventas/useVentas";
import { useSucursals } from "@/composables/sucursals/useSucursals";
import { useClientes } from "@/composables/clientes/useClientes";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";

const { mobile, cambiarUrl } = useMenu();
const { oVenta, limpiarVenta } = useVentas();
const loading = ref(true);
let form = useForm(oVenta);
const { flash, auth } = usePage().props;
const user = ref(auth.user);
const cod_prod = ref("");
const cod_prod_ref = ref(null);
const { getClientes } = useClientes();
const { getSucursals } = useSucursals();

const listClientes = ref([]);
const listSucursals = ref([]);

const tituloDialog = computed(() => {
    return oVenta.id == 0 ? `Agregar Venta` : `Editar Venta`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("ventas.store")
            : route("ventas.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarVenta();
            cambiarUrl(route("ventas.index"));
        },
        onError: (err) => {
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

const cargarListas = async () => {
    listClientes.value = await getClientes();
    listSucursals.value = await getSucursals();
};

const agregarProducto = () => {
    if (form.sucursal_id && form.sucursal_id != "") {
        if ("" + cod_prod.value.trim() != "") {
            if (verificaCodigo(cod_prod.value) < 0) {
                axios
                    .get(route("producto_barras.getByCod"), {
                        params: {
                            codigo: cod_prod.value,
                            sucursal_id: form.sucursal_id,
                            venta: true,
                        },
                    })
                    .then((response) => {
                        const producto_barra = response.data;
                        if (producto_barra && producto_barra.producto) {
                            form.producto_barras.push({
                                id: producto_barra.id,
                                producto_id: producto_barra.producto_id,
                                codigo: producto_barra.codigo,
                                lugar: producto_barra.lugar,
                                sucursal_id: producto_barra.sucursal_id,
                                venta_id: 0,
                                venta_detalle_id: 0,
                            });
                            let index_detalle = verificaVentaDetalle(
                                producto_barra.producto_id
                            );
                            if (index_detalle < 0) {
                                form.venta_detalles.push({
                                    id: 0,
                                    venta_id: 0,
                                    producto_id: producto_barra.producto_id,
                                    cantidad: 1,
                                    producto: producto_barra.producto,
                                    precio: producto_barra.producto.precio,
                                    subtotal: producto_barra.producto.precio,
                                });
                            } else {
                                let cantidad_actual =
                                    form.venta_detalles[index_detalle].cantidad;
                                cantidad_actual++;
                                let subtotal =
                                    cantidad_actual *
                                    producto_barra.producto.precio;
                                form.venta_detalles[index_detalle].cantidad =
                                    cantidad_actual;
                                form.venta_detalles[index_detalle].precio =
                                    producto_barra.producto.precio;
                                form.venta_detalles[index_detalle].subtotal =
                                    subtotal;
                            }
                        } else {
                            Swal.fire({
                                icon: "info",
                                title: "Error",
                                text: `No se encontro ningun producto con ese código`,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: `Aceptar`,
                            });
                        }

                        calculaTotal();
                    });
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Error",
                    text: `Ese producto ya fue agregado`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
            }
            cod_prod.value = "";
            cod_prod_ref.value.focus();
        }
    } else {
        cod_prod.value = "";
        cod_prod_ref.value.focus();
        Swal.fire({
            icon: "info",
            title: "Error",
            text: `Selecciona una sucursal primero`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const quitarProducto = (index_detalle) => {
    let id = form.venta_detalles[index_detalle].id;
    let producto_id = form.venta_detalles[index_detalle].producto_id;

    // limpiar los codigos
    let producto_barras_filter = form.producto_barras.filter(
        (item) => ![producto_id].includes(item.producto_id)
    );
    form.producto_barras = producto_barras_filter;

    if (id != 0) {
        form.eliminados.push(id);
    }

    form.venta_detalles.splice(index_detalle, 1);

    calculaTotal();
};

const verificaVentaDetalle = (producto_id) => {
    // Encuentra el índice del elemento cuyo código sea igual a cod
    return form.venta_detalles.findIndex(
        (venta_detalle) => venta_detalle.producto_id === producto_id
    );
};
const verificaCodigo = (cod) => {
    // Encuentra el índice del elemento cuyo código sea igual a cod
    return form.producto_barras.findIndex(
        (producto) => producto.codigo === cod
    );
};

const calculaTotal = () => {
    let total = 0;
    let descuento = form.descuento;
    if (form.venta_detalles.length > 0) {
        if (("" + form.descuento).trim() == "") {
            descuento = 0;
        }
        let p_descuento = descuento / 100;

        form.venta_detalles.forEach((elem, index) => {
            total += parseFloat(elem.subtotal);
        });

        form.total = total.toFixed(2);
        form.total_final = parseFloat(total) * (1 - p_descuento);
        form.total_final = parseFloat(form.total_final).toFixed(2);
    }
};

onMounted(() => {
    cargarListas();
    if (user.value.tipo != "ADMINISTRADOR") {
        form.sucursal_id = user.sucursal_id;
    }
});
</script>

<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <Link
                        :href="route('ventas.index')"
                        class="btn btn-secondary"
                        ><i class="fa fa-arrow-left"></i> Volver</Link
                    >
                    <panel-toolbar
                        style="margin-left: auto"
                        :mostrar_loading="loading"
                    />
                </div>
                <div class="panel-body">
                    <form @submit.prevent="enviarFormulario">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div
                                        class="col-12"
                                        v-if="user.tipo == 'ADMINISTRADOR'"
                                    >
                                        <template v-if="form.id == 0">
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
                                            <label>Sucursal</label>
                                            <input
                                                class="form-control readonly"
                                                :class="{
                                                    'parsley-error':
                                                        form.errors
                                                            ?.sucursal_id,
                                                }"
                                                v-model="form.sucursal.nombre"
                                                readonly
                                            />
                                        </template>
                                    </div>
                                    <div class="col-12">
                                        <label>Seleccionar Cliente*</label>
                                        <select
                                            class="form-select"
                                            :class="{
                                                'parsley-error':
                                                    form.errors?.cliente_id,
                                            }"
                                            v-model="form.cliente_id"
                                        >
                                            <option value="">
                                                - Seleccione -
                                            </option>
                                            <option
                                                v-for="item in listClientes"
                                                :value="item.id"
                                            >
                                                {{ item.nombre }} |
                                                {{ item.ci }}
                                            </option>
                                        </select>
                                        <ul
                                            v-if="form.errors?.cliente_id"
                                            class="parsley-errors-list filled"
                                        >
                                            <li class="parsley-required">
                                                {{ form.errors?.cliente_id }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-12">
                                    <label>Agregar productos*</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        ref="cod_prod_ref"
                                        v-model="cod_prod"
                                        @keypress.enter.prevent="
                                            agregarProducto()
                                        "
                                    />
                                    <small class="fs-12px text-gray-500-darker"
                                        >Mantener el campo de texto seleccionado
                                        para agregar los productos</small
                                    >
                                </div>
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-principal">
                                                <th class="text-white">N°</th>
                                                <th class="text-white">
                                                    Producto
                                                </th>
                                                <th class="text-white">
                                                    Cantidad
                                                </th>
                                                <th class="text-white">P/U</th>
                                                <th class="text-white">
                                                    Subtotal
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    item, index_detalle
                                                ) in form.venta_detalles"
                                            >
                                                <td>{{ index_detalle + 1 }}</td>
                                                <td>
                                                    {{ item.producto?.nombre }}
                                                </td>
                                                <td>{{ item.cantidad }}</td>
                                                <td>{{ item.precio }}</td>
                                                <td>{{ item.subtotal }}</td>
                                                <td>
                                                    <button
                                                        class="btn btn-danger btn-sm"
                                                        @click.prevent="
                                                            quitarProducto(
                                                                index_detalle
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
                                                v-if="
                                                    form.venta_detalles
                                                        .length == 0
                                                "
                                            >
                                                <td
                                                    colspan="6"
                                                    class="text-center"
                                                >
                                                    No se agregó ningun producto
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <label>Total*</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control readonly"
                                        :class="{
                                            'parsley-error': form.errors?.total,
                                        }"
                                        v-model="form.total"
                                        readonly
                                    />
                                    <ul
                                        v-if="form.errors?.total"
                                        class="parsley-errors-list filled"
                                    >
                                        <li class="parsley-required">
                                            {{ form.errors?.total }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <label>Descuento (0-100%)*</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        :class="{
                                            'parsley-error':
                                                form.errors?.descuento,
                                        }"
                                        v-model="form.descuento"
                                        @change="calculaTotal()"
                                        @keyup.prevent="calculaTotal()"
                                    />
                                    <ul
                                        v-if="form.errors?.descuento"
                                        class="parsley-errors-list filled"
                                    >
                                        <li class="parsley-required">
                                            {{ form.errors?.descuento }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <label>Total final*</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control readonly"
                                        :class="{
                                            'parsley-error':
                                                form.errors?.total_final,
                                        }"
                                        v-model="form.total_final"
                                        readonly
                                    />
                                    <ul
                                        v-if="form.errors?.total_final"
                                        class="parsley-errors-list filled"
                                    >
                                        <li class="parsley-required">
                                            {{ form.errors?.total_final }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 mt-2">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                </div>
                                <div class="col-12 mt-1" v-if="form.id != 0">
                                    <button class="btn btn-info w-100 mt-2">
                                        <i class="fa fa-file-alt"></i> Imprimir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
