<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useSalidaProductos } from "@/composables/salida_productos/useSalidaProductos";
import { useTipoProductos } from "@/composables/tipo_productos/useTipoProductos";
import { watch, ref, computed, defineEmits } from "vue";
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
const { getTipoProductos } = useTipoProductos();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oSalidaProducto.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarListas();
            form = useForm(oSalidaProducto.value);
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
    return accion.value == 0 ? `Agregar registro` : `Editar registro`;
});

const listTipoProductos = ref([]);
const listProductos = ref([]);

const cargarListas = async () => {
    listTipoProductos.value = await getTipoProductos();
};

const getProductos = (tipo_producto_id) => {
    if (tipo_producto_id != "") {
        axios
            .get(route("productos.byTipoProducto"), {
                params: {
                    tipo_producto_id: tipo_producto_id,
                },
            })
            .then((response) => {
                listProductos.value = response.data.productos;
            });
    }
};

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("salida_productos.store")
            : route("salida_productos.update", form.id);

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
            limpiarSalidaProducto();
            emits("envio-formulario");
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

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    getProductos(oSalidaProducto.value.tipo_producto_id);
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
};
</script>

<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024" persistent scrollable>
            <v-card>
                <v-card-title class="border-b bg-primary pa-5">
                    <v-icon
                        icon="mdi-close"
                        class="float-right"
                        @click="cerrarDialog"
                    ></v-icon>

                    <v-icon
                        :icon="accion == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form>
                            <v-row>
                                <v-col cols="12" sm="6" md="6">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.tipo_producto_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.tipo_producto_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo_producto_id
                                                ? form.errors?.tipo_producto_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        no-data-text="Sin registros"
                                        clearable
                                        :items="listTipoProductos"
                                        item-value="id"
                                        item-title="nombre"
                                        label="Seleccionar tipo de producto*"
                                        v-model="form.tipo_producto_id"
                                        @update:modelValue="getProductos"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.producto_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.producto_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.producto_id
                                                ? form.errors?.producto_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        no-data-text="Sin registros"
                                        clearable
                                        :items="listProductos"
                                        item-value="id"
                                        item-title="nombre"
                                        label="Seleccionar producto*"
                                        v-model="form.producto_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        type="number"
                                        :hide-details="
                                            form.errors?.cantidad ? false : true
                                        "
                                        :error="
                                            form.errors?.cantidad ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.cantidad
                                                ? form.errors?.cantidad
                                                : ''
                                        "
                                        variant="underlined"
                                        color="primary"
                                        label="Cantidad de Salida*"
                                        required
                                        density="compact"
                                        v-model="form.cantidad"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        type="date"
                                        :hide-details="
                                            form.errors?.fecha_salida
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.fecha_salida
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha_salida
                                                ? form.errors?.fecha_salida
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        label="Fecha de Salida*"
                                        v-model="form.fecha_salida"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
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
                                        v-model="form.descripcion"
                                        required
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
                <v-card-actions class="border-t">
                    <v-spacer></v-spacer>
                    <v-btn
                        color="grey-darken-4"
                        variant="text"
                        @click="cerrarDialog"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        class="bg-primary"
                        prepend-icon="mdi-content-save"
                        @click="enviarFormulario"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
