import { useEffect, useState } from 'react';

export const valoresIniciales = {
    nombre: '',
    apellido: '',
    cedula: '',
    username: '',
    email: '',
    facultad_id: '',
    carrera_id: '',
    semestre: '',
    password: '',
    password_confirmation: '',
};

const claseInput =
    'w-full rounded border border-slate-300 bg-white px-3 py-2 focus:border-indigo-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-400';

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
                    className={claseInput}
                    {...props}
                />
            )}
            {errors[name] && <p className="mt-1 text-sm text-red-600">{errors[name]}</p>}
        </div>
    );
}

/**
 * Carga del servidor las carreras de la facultad escogida.
 * Cada vez que cambia la facultad se hace GET /facultades/{id}/carreras.
 */
function useCarreras(facultadId) {
    const [carreras, setCarreras] = useState([]);
    const [cargando, setCargando] = useState(false);

    useEffect(() => {
        setCarreras([]);
        if (!facultadId) return;

        const control = new AbortController();
        setCargando(true);

        fetch(`/facultades/${facultadId}/carreras`, {
            headers: { Accept: 'application/json' },
            signal: control.signal,
        })
            .then((r) => (r.ok ? r.json() : []))
            .then(setCarreras)
            .catch(() => {})
            .finally(() => !control.signal.aborted && setCargando(false));

        // Si el usuario cambia de facultad antes de que responda, se cancela la petición anterior
        return () => control.abort();
    }, [facultadId]);

    return { carreras, cargando };
}

/**
 * Campos de un estudiante. Se usa en el registro público y en el CRUD del admin.
 * editando = true -> la contraseña es opcional (vacía = se conserva la actual).
 */
export default function CamposEstudiante({ form, facultades = [], editando = false }) {
    const { data, setData } = form;
    const { carreras, cargando } = useCarreras(data.facultad_id);

    // Al cambiar de facultad se borra la carrera elegida: debe escogerse una de la nueva lista
    const cambiarFacultad = (e) => setData((d) => ({ ...d, facultad_id: e.target.value, carrera_id: '' }));

    return (
        <div className="grid gap-5 sm:grid-cols-2">
            <Campo form={form} name="nombre" label="Nombre" autoComplete="given-name" />
            <Campo form={form} name="apellido" label="Apellido" autoComplete="family-name" />
            <Campo
                form={form}
                name="cedula"
                label="Cédula de identidad"
                inputMode="numeric"
                maxLength={11}
                placeholder="Ej: 1710034065"
            />
            <Campo form={form} name="username" label="Usuario" autoComplete="username" />
            <Campo form={form} name="email" label="Correo electrónico" type="email" autoComplete="email" />
            <Campo form={form} name="semestre" label="Semestre" type="number" min="1" max="12" />

            <Campo form={form} name="facultad_id" label="Facultad">
                <select id="facultad_id" value={data.facultad_id} onChange={cambiarFacultad} className={claseInput}>
                    <option value="">Selecciona una facultad…</option>
                    {facultades.map((f) => (
                        <option key={f.id} value={f.id}>
                            {f.nombre}
                        </option>
                    ))}
                </select>
            </Campo>

            <Campo form={form} name="carrera_id" label="Carrera">
                <select
                    id="carrera_id"
                    value={data.carrera_id}
                    onChange={(e) => setData('carrera_id', e.target.value)}
                    disabled={!data.facultad_id || cargando}
                    className={claseInput}
                >
                    <option value="">
                        {!data.facultad_id
                            ? 'Primero selecciona una facultad'
                            : cargando
                              ? 'Cargando carreras…'
                              : 'Selecciona una carrera…'}
                    </option>
                    {carreras.map((c) => (
                        <option key={c.id} value={c.id}>
                            {c.nombre}
                        </option>
                    ))}
                </select>
            </Campo>

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
