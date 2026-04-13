<script>
export default {
    name: 'ItemList',

    props: {
        items: {
            type: Array,
            default: () => [],
        },

        selectedId: {
            type: [String, null],
            default: null,
        },

        labels: {
            type: Object,
            default: () => ({}),
        },
    },

    emits: ['select', 'edit', 'delete', 'add'],
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600">
                {{ labels.listHint ?? 'Select a row to highlight it. Changes stay in the browser only.' }}
            </p>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-shop-accent px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-shop-accent-hover"
                @click="$emit('add')"
            >
                <span class="text-lg leading-none">+</span>
                {{ labels.addItem ?? 'Add item' }}
            </button>
        </div>

        <ul
            v-if="items.length"
            class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            role="list"
        >
            <li
                v-for="item in items"
                :key="item.id"
            >
                <div
                    class="flex cursor-pointer flex-col gap-3 p-4 transition-colors sm:flex-row sm:items-center sm:justify-between"
                    :class="selectedId === item.id ? 'bg-emerald-50/80' : 'hover:bg-slate-50'"
                    role="button"
                    tabindex="0"
                    @click="$emit('select', item.id)"
                    @keydown.enter="$emit('select', item.id)"
                >
                    <div class="flex min-w-0 flex-1 gap-3">
                        <div
                            class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200/80"
                        >
                            <img
                                v-if="item.imageUrl"
                                :src="item.imageUrl"
                                alt=""
                                class="h-full w-full object-cover"
                            >

                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center text-xs text-slate-400"
                            >
                                {{ labels.noImage ?? 'No image' }}
                            </div>
                        </div>

                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900">
                                {{ item.title }}
                            </p>

                            <p class="mt-0.5 line-clamp-2 text-sm text-slate-600">
                                {{ item.description || (labels.emptyDescription ?? '—') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 gap-2 self-end sm:self-center">
                        <button
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:border-shop-primary hover:text-shop-primary"
                            @click.stop="$emit('edit', item)"
                        >
                            {{ labels.edit ?? 'Edit' }}
                        </button>

                        <button
                            type="button"
                            class="rounded-lg border border-red-100 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100"
                            @click.stop="$emit('delete', item.id)"
                        >
                            {{ labels.delete ?? 'Delete' }}
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <div
            v-else
            class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 px-6 py-12 text-center"
        >
            <p class="text-slate-600">
                {{ labels.emptyState ?? 'No items yet. Add one to get started.' }}
            </p>
        </div>
    </div>
</template>
