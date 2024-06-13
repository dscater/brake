<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useProductos } from "@/composables/productos/useProductos";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useTipoProductos } from "@/composables/tipo_productos/useTipoProductos";
import { watch, ref, computed, defineEmits, onMounted } from "vue";
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
const { getTipoProductos } = useTipoProductos();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listCategorias = ref([]);
const listTipoProductos = ref([]);
let form = useForm(oProducto.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargaListas();
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

const tituloDialog = computed(() => {
    return accion.value == 0 ? `Agregar registro` : `Editar registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("productos.store")
            : route("productos.update", form.id);

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
            limpiarProducto();
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
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
};

const imagen = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const cargaListas = async () => {
    listCategorias.value = await getCategorias();
    listTipoProductos.value = await getTipoProductos();
};
onMounted(() => {});
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
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nombre ? false : true
                                        "
                                        :error="
                                            form.errors?.nombre ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.nombre
                                                ? form.errors?.nombre
                                                : ''
                                        "
                                        variant="underlined"
                                        color="primary"
                                        label="Nombre de Producto*"
                                        required
                                        density="compact"
                                        v-model="form.nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
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
                                        variant="underlined"
                                        color="primary"
                                        label="Precio*"
                                        type="number"
                                        step="0.01"
                                        required
                                        density="compact"
                                        v-model="form.precio"
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
                                        variant="underlined"
                                        color="primary"
                                        label="Descripción"
                                        required
                                        density="compact"
                                        v-model="form.descripcion"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.categoria_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.categoria_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.categoria_id
                                                ? form.errors?.categoria_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        no-data-text="Sin registros"
                                        clearable
                                        :items="listCategorias"
                                        item-value="id"
                                        item-title="nombre"
                                        label="Categoría de Producto*"
                                        v-model="form.categoria_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
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
                                        label="Tipo de Producto*"
                                        v-model="form.tipo_producto_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.stock_minimo
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.stock_minimo
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.stock_minimo
                                                ? form.errors?.stock_minimo
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        type="number"
                                        step="0.01"
                                        label="Stock Mínimo*"
                                        v-model="form.stock_minimo"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-file-input
                                        :hide-details="
                                            form.errors?.imagen ? false : true
                                        "
                                        :error="
                                            form.errors?.imagen ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.imagen
                                                ? form.errors?.imagen
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="primary"
                                        accept="image/png, image/jpeg, image/bmp"
                                        placeholder="Imagen referencial"
                                        prepend-icon="mdi-camera"
                                        label="Imagen referencial"
                                        @change="cargaArchivo($event, 'imagen')"
                                        ref="imagen"
                                    ></v-file-input>
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
