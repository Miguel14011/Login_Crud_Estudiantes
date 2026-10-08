import { Head, Link, useForm } from '@inertiajs/react';

const campos = [
    { name: 'name', label: 'Nombre completo', type: 'text', autoComplete: 'name' },
    { name: 'username', label: 'Usuario', type: 'text', autoComplete: 'username' },
    { name: 'email', label: 'Correo electrónico', type: 'email', autoComplete: 'email' },
    { name: 'password', label: 'Contraseña (mínimo 8 caracteres)', type: 'password', autoComplete: 'new-password' },
    { name: 'password_confirmation', label: 'Confirmar contraseña', type: 'password', autoComplete: 'new-password' },
];

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register', { onFinish: () => reset('password', 'password_confirmation') });
    };

    return (
        <div className="flex min-h-screen items-center justify-center px-4 py-8">
            <Head title="Crear cuenta" />

            <form onSubmit={submit} className="w-full max-w-sm space-y-5 rounded-lg bg-white p-8 shadow">
                <div className="text-center">
                    <h1 className="text-2xl font-semibold">Crear cuenta</h1>
                    <p className="mt-1 text-sm text-slate-500">Regístrate para gestionar los estudiantes</p>
                </div>

                {campos.map((c, i) => (
                    <div key={c.name}>
                        <label htmlFor={c.name} className="mb-1 block text-sm font-medium">
                            {c.label}
                        </label>
                        <input
                            id={c.name}
                            type={c.type}
                            autoComplete={c.autoComplete}
                            autoFocus={i === 0}
                            value={data[c.name]}
                            onChange={(e) => setData(c.name, e.target.value)}
                            className="w-full rounded border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"
                        />
                        {errors[c.name] && <p className="mt-1 text-sm text-red-600">{errors[c.name]}</p>}
                    </div>
                ))}

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full rounded bg-indigo-600 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {processing ? 'Creando cuenta…' : 'Registrarme'}
                </button>

                <p className="text-center text-sm text-slate-500">
                    ¿Ya tienes cuenta?{' '}
                    <Link href="/login" className="font-medium text-indigo-600 hover:underline">
                        Inicia sesión
                    </Link>
                </p>
            </form>
        </div>
    );
}
