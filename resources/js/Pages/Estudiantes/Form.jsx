import { Link } from '@inertiajs/react';

const campos = [
    { name: 'nombre', label: 'Nombre', type: 'text' },
    { name: 'apellido', label: 'Apellido', type: 'text' },
    { name: 'email', label: 'Email', type: 'email' },
    { name: 'carrera', label: 'Carrera', type: 'text' },
    { name: 'semestre', label: 'Semestre', type: 'number', min: 1, max: 12 },
];

// Formulario compartido por Crear y Editar
export default function Form({ form, onSubmit, submitLabel }) {
    const { data, setData, errors, processing } = form;

    return (
        <form onSubmit={onSubmit} className="space-y-5 rounded-lg bg-white p-6 shadow">
            <div className="grid gap-5 sm:grid-cols-2">
                {campos.map((c) => (
                    <div key={c.name}>
                        <label htmlFor={c.name} className="mb-1 block text-sm font-medium">
                            {c.label}
                        </label>
                        <input
                            id={c.name}
                            type={c.type}
                            min={c.min}
                            max={c.max}
                            value={data[c.name]}
                            onChange={(e) => setData(c.name, e.target.value)}
                            className="w-full rounded border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"
                        />
                        {errors[c.name] && <p className="mt-1 text-sm text-red-600">{errors[c.name]}</p>}
                    </div>
                ))}
            </div>

            <div className="flex justify-end gap-3">
                <Link href="/estudiantes" className="rounded border border-slate-300 px-4 py-2 hover:bg-slate-50">
                    Cancelar
                </Link>
                <button
                    type="submit"
                    disabled={processing}
                    className="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {submitLabel}
                </button>
            </div>
        </form>
    );
}
