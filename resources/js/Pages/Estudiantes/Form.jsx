import { Link } from '@inertiajs/react';
import CamposEstudiante from '../../Components/CamposEstudiante';

// Formulario del CRUD (crear y editar)
export default function Form({ form, onSubmit, submitLabel, editando = false }) {
    return (
        <form onSubmit={onSubmit} className="space-y-5 rounded-lg bg-white p-6 shadow">
            <CamposEstudiante form={form} editando={editando} />

            <div className="flex justify-end gap-3">
                <Link href="/estudiantes" className="rounded border border-slate-300 px-4 py-2 hover:bg-slate-50">
                    Cancelar
                </Link>
                <button
                    type="submit"
                    disabled={form.processing}
                    className="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {submitLabel}
                </button>
            </div>
        </form>
    );
}
