<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useIngresos } from "@/composables/ingresos/useIngresos";
import { useProveedors } from "@/composables/proveedors/useProveedors";
import { useTipoIngresos } from "@/composables/tipo_ingresos/useTipoIngresos";
import { useProductos } from "@/composables/productos/useProductos";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";

const { mobile, cambiarUrl } = useMenu();
const { oIngreso, limpiarIngreso } = useIngresos();
let form = useForm(oIngreso);

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getProveedors } = useProveedors();
const { getTipoIngresos } = useTipoIngresos();
const { getProductos } = useProductos();

const listProveedors = ref([]);
const listTipoIngresos = ref([]);
const listProductos = ref([]);
const producto_id = ref(null);
const cantidad = ref(0);
const tituloDialog = computed(() => {
    return oIngreso.id == 0 ? `Registrar Ingreso` : `Editar Ingreso`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("ingresos.store")
            : route("ingresos.update", form.id);

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
            limpiarIngreso();
            cambiarUrl(route("ingresos.index"));
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
    listProveedors.value = await getProveedors();
    listTipoIngresos.value = await getTipoIngresos();
    listProductos.value = await getProductos();
};

const agregarIngresoDetalle = () => {
    if (producto_id.value != "" && cantidad.value != "" && cantidad.value > 0) {
        form.ingreso_detalles.push({
            id: 0,
            ingreso_id: 0,
            producto_id: producto_id.value,
            producto: getProducto(producto_id.value),
            cantidad: cantidad.value,
        });

        producto_id.value = null;
        cantidad.value = 0;
    } else {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `Debes seleccionar el producto e indicar una cantidad`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const quitarIngresoDetalle = (index, id) => {
    if (id != 0) {
        form.eliminados.push(id);
    }
    form.ingreso_detalles.splice(index, 1);
};

const getProducto = (id) => {
    let producto = listProductos.value.filter((elem) => elem.id == id)[0];
    return producto;
};

onMounted(() => {
    if (form.id && form.id != "") {
    } else {
    }
    cargarListas();
});
</script>

<template>
    <v-row class="mt-0">
        <v-col cols="12" class="d-flex justify-end">
            <template v-if="mobile">
                <v-btn
                    icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('ingresos.index'))"
                ></v-btn>
                <v-btn
                    icon="mdi-content-save"
                    color="primary"
                    @click="enviarFormulario"
                ></v-btn>
            </template>
            <template v-if="!mobile">
                <v-btn
                    prepend-icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('ingresos.index'))"
                >
                    Volver</v-btn
                >
                <v-btn
                    :prepend-icon="
                        oIngreso.id != 0 ? 'mdi-sync' : 'mdi-content-save'
                    "
                    color="primary"
                    @click="enviarFormulario"
                >
                    <span
                        v-text="
                            oIngreso.id != 0
                                ? 'Actualizar Ingreso'
                                : 'Guardar Ingreso'
                        "
                    ></span
                ></v-btn>
            </template>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="border-b bg-primary pa-5">
                    <v-icon
                        :icon="form.id == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form @submit.prevent="enviarFormulario">
                            <v-row>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.proveedor_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.proveedor_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.proveedor_id
                                                ? form.errors?.proveedor_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        no-data-text="Sin registros"
                                        clearable
                                        :items="listProveedors"
                                        item-value="id"
                                        item-title="razon_social"
                                        label="Seleccionar proveedor*"
                                        v-model="form.proveedor_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.tipo_ingreso_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.tipo_ingreso_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo_ingreso_id
                                                ? form.errors?.tipo_ingreso_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        no-data-text="Sin registros"
                                        clearable
                                        :items="listTipoIngresos"
                                        item-value="id"
                                        item-title="nombre"
                                        label="Seleccionar Tipo de Ingreso*"
                                        v-model="form.tipo_ingreso_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.precio ? false : true
                                        "
                                        :error="
                                            form.errors?.precio ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.precio
                                                ? form.errors?.precio
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        label="Precio"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        required
                                        v-model="form.precio"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nro_factura
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.nro_factura
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.nro_factura
                                                ? form.errors?.nro_factura
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        label="Número de factura*"
                                        required
                                        v-model="form.nro_factura"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.descripcion
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.descripcion
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.descripcion
                                                ? form.errors?.descripcion
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        label="Descripción"
                                        required
                                        v-model="form.descripcion"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fecha_ingreso
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.fecha_ingreso
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha_ingreso
                                                ? form.errors?.fecha_ingreso
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        type="date"
                                        label="Fecha de Ingreso*"
                                        required
                                        v-model="form.fecha_ingreso"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="bg-primary pa-5">
                    <span class="text-h5">Seleccionar Productos</span>
                </v-card-title>
                <v-card-text>
                    <v-row class="py-3">
                        <v-col cols="12" md="6">
                            <v-autocomplete
                                :hide-details="true"
                                density="compact"
                                variant="underlined"
                                color="primary"
                                no-data-text="Sin registros"
                                clearable
                                :items="listProductos"
                                item-value="id"
                                item-title="nombre"
                                label="Seleccionar Producto"
                                v-model="producto_id"
                                required
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                :hide-details="true"
                                density="compact"
                                variant="underlined"
                                color="primary"
                                label="Cantidad"
                                type="number"
                                step="0.01"
                                min="0"
                                required
                                v-model="cantidad"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-btn
                                prepend-icon="mdi-plus"
                                color="blue"
                                class="mt-2"
                                block
                                :disabled="
                                    !producto_id || !cantidad || cantidad < 0.01
                                "
                                @click="agregarIngresoDetalle"
                                >Agregar</v-btn
                            >
                            <span class="text-caption" v-if="!producto_id"
                                >Debes seleccionar un producto</span
                            >
                            <span
                                class="text-caption d-block"
                                v-if="!cantidad || cantidad < 0.01"
                                >Debes ingresar la cantidad</span
                            >
                            <v-table>
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            item, index
                                        ) in form.ingreso_detalles"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            {{ item.producto.nombre }}
                                        </td>
                                        <td class="text-center">
                                            {{ item.cantidad }}
                                        </td>
                                        <td class="text-right">
                                            <v-btn
                                                size="small"
                                                icon="mdi-trash-can"
                                                color="red-darken-3"
                                                @click="
                                                    quitarIngresoDetalle(
                                                        index,
                                                        item.id
                                                    )
                                                "
                                            ></v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped></style>
