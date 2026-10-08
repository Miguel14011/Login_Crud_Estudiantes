const carreras = [
    'Ingeniería de Sistemas',
    'Ingeniería Industrial',
    'Administración de Empresas',
    'Contaduría Pública',
    'Psicología',
];

export const valoresIniciales = {
    nombre: '',
    apellido: '',
    username: '',
    email: '',
    carrera: '',
    semestre: '',
    password: '',
    password_confirmation: '',
};

function Campo({ form, name, label, children, ...props }) {
    const { data, setData, errors } = form;

    return (
        <div>
            <label htmlFor={name} className="mb-1 block text-sm font-medium">
                {label}
            </label>
            {children ?? (
                <input
                    id={name}
                    value={data[name]}
                    onChange={(e) => setData(name, e.target.value)}
                    className="w-full rounded border border-slate-300 bg-white px-3 py-2 focus:border-indigo-500 focus:outline-none"
                    {...props}
                />
            )}
            {errors[name] && <p className="mt-1 text-sm text-red-600">{errors[name]}</p>}
        </div>
    );
}

/**
 * Campos de un estudiante. Se usa en el registro público y en el CRUD del admin.
 * editando = true -> la contraseña es opcional (vacía = se conserva la actual).
 */
export default function CamposEstudiante({ form, editando = false }) {
    const { data, setData } = form;

    return (
        <div className="grid gap-5 sm:grid-cols-2">
            <Campo form={form} name="nombre" label="Nombre" autoComplete="given-name" />
            <Campo form={form} name="apellido" label="Apellido" autoComplete="family-name" />
            <Campo form={form} name="username" label="Usuario" autoComplete="username" />
            <Campo form={form} name="email" label="Correo electrónico" type="email" autoComplete="email" />
            <Campo form={form} name="carrera" label="Carrera">
                <select
                    id="carrera"
                    value={data.carrera}
                    onChange={(e) => setData('carrera', e.target.value)}
                    className="w-full rounded border border-slate-300 bg-white px-3 py-2 focus:border-indigo-500 focus:outline-none"
                >
                    <option value="">Selecciona una carrera…</option>
                    {carreras.map((c) => (
                        <option key={c}>{c}</option>
                    ))}
                </select>
            </Campo>
            <Campo form={form} name="semestre" label="Semestre" type="number" min="1" max="12" />
            <Campo
                form={form}
                name="password"
                label={editando ? 'Nueva contraseña (opcional)' : 'Contraseña (mínimo 8 caracteres)'}
                type="password"
                autoComplete="new-password"
                placeholder={editando ? 'Déjala vacía para no cambiarla' : ''}
            />
            <Campo
                form={form}
                name="password_confirmation"
                label="Confirmar contraseña"
                type="password"
                autoComplete="new-password"
            />
        </div>
    );
}
