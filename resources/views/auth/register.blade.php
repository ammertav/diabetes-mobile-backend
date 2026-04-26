<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Register | Clinical Sanctuary</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface": "#191c1e",
                        "inverse-surface": "#2d3133",
                        "surface-bright": "#f7f9fb",
                        "surface-variant": "#e0e3e5",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "secondary-fixed-dim": "#4edea3",
                        "surface-dim": "#d8dadc",
                        "on-error-container": "#93000a",
                        "secondary": "#006c49",
                        "on-surface-variant": "#434655",
                        "on-error": "#ffffff",
                        "surface-container": "#eceef0",
                        "inverse-primary": "#b4c5ff",
                        "background": "#f7f9fb",
                        "surface-container-highest": "#e0e3e5",
                        "primary-container": "#2563eb",
                        "tertiary-fixed": "#ffdad7",
                        "on-secondary-container": "#00714d",
                        "secondary-fixed": "#6ffbbe",
                        "on-background": "#191c1e",
                        "on-tertiary-container": "#ffecea",
                        "on-secondary-fixed-variant": "#005236",
                        "tertiary": "#ab0b1c",
                        "on-primary-container": "#eeefff",
                        "tertiary-fixed-dim": "#ffb3ad",
                        "surface": "#f7f9fb",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed": "#410004",
                        "primary-fixed": "#dbe1ff",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-secondary-fixed": "#002113",
                        "surface-container-high": "#e6e8ea",
                        "surface-tint": "#0053db",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed-variant": "#930013",
                        "on-tertiary": "#ffffff",
                        "secondary-container": "#6cf8bb",
                        "error": "#ba1a1a",
                        "outline": "#737686",
                        "primary": "#004ac6",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-fixed-variant": "#003ea8",
                        "on-primary-fixed": "#00174b",
                        "outline-variant": "#c3c6d7",
                        "tertiary-container": "#cf2c30"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.5rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .bg-signature-gradient {
            background: linear-gradient(135deg, #004ac6 0%, #2563eb 100%);
        }
    </style>
</head>

<body class="bg-background font-body text-on-surface flex min-h-screen">
    <!-- Left Visual Panel (Editorial Asymmetry) -->
    <div
        class="hidden lg:flex w-5/12 bg-surface-container-low flex-col justify-between p-16 relative overflow-hidden">
        <div class="z-10">
            <h1
                class="font-headline text-3xl font-bold tracking-tighter text-primary uppercase mb-8">
                Clinical Sanctuary</h1>
            <div class="space-y-6 max-w-md">
                <h2
                    class="font-headline text-5xl font-extrabold leading-tight tracking-tight">
                    Precision Care for Diabetes Management.</h2>
                <p class="text-on-surface-variant text-lg leading-relaxed">Join
                    a network of elite endocrinologists and clinical staff
                    providing data-driven excellence in patient monitoring.</p>
            </div>
        </div>
        <!-- Decorative Data Card (High-End Aesthetic) -->
        <div
            class="absolute bottom-16 -right-5 w-80 p-8 bg-surface-container-lowest rounded-xl shadow-2xl z-10 transform -rotate-2">
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center">
                    <span
                        class="material-symbols-outlined text-on-secondary-container"
                        data-icon="bloodtype">bloodtype</span>
                </div>
                <div>
                    <div
                        class="text-[10px] font-label font-bold uppercase tracking-widest text-on-surface-variant">
                        Real-time Syncing</div>
                    <div class="font-headline font-bold text-on-surface">Glucose
                        Trends</div>
                </div>
            </div>
            <div
                class="h-1 bg-surface-container-highest rounded-full overflow-hidden mb-2">
                <div class="bg-secondary w-3/4 h-full"></div>
            </div>
            <div
                class="flex justify-between text-[11px] font-label font-medium opacity-60">
                <span>OPTIMAL RANGE</span>
                <span>74% STABILITY</span>
            </div>
        </div>
        <div
            class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
            <img alt="minimalist abstract medical glass texture with soft blue and white highlights reflecting clean clinical laboratory environment"
                class="w-full h-full object-cover"
                data-alt="minimalist abstract medical glass texture with soft blue and white highlights reflecting clean clinical laboratory environment"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuASG_05mIz6rDZs4lflLrYQy0JBtDny1JsoQv2BGjEfmZTCPMjM_d12SU0s9Pwx_vxqcVXcec15TAx3Y2k_R0j47b-tzSyn3-luKpJr5kMMwo5CJQ22K8kUtugVbwRUTU1tfeP6-acPpnaMEDpxEDsoGbKwCwNOoWXQ7PCmo0d-dIcX9QbM_tBK_03-f1hGHF_VA3fHKCOz4NNCgqrP_8njIXhp5AxncMXJII66QRK_02d24JevfqRwoTI2JDhat3IGp0Enfguuqmk" />
        </div>
    </div>
    <!-- Right Registration Content (Task-Focused Canvas) -->
    <main
        class="flex-1 flex flex-col items-center justify-center p-8 md:p-16 bg-surface">
        <div class="w-full max-w-md">
            <!-- Header for Mobile/Tablets (Hidden on LG) -->
            <div class="lg:hidden mb-12 text-center">
                <h1
                    class="font-headline text-2xl font-black tracking-tighter text-primary uppercase">
                    Clinical Sanctuary</h1>
            </div>
            <div class="mb-10 text-center lg:text-left">
                <h3 class="font-headline text-3xl font-bold mb-2">Create your
                    account</h3>
                <p
                    class="text-on-surface-variant font-label text-sm tracking-tight">
                    Access the provider dashboard and HIPAA-compliant patient
                    records.</p>
            </div>
            <!-- Registration Form -->
            <form class="space-y-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label
                        class="block font-label text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1"
                        for="name">Full Name</label>
                    <input
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-highest border-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all text-sm font-medium"
                        id="name" placeholder="Dr. Sarah Mitchell"
                        type="text" />
                </div>
                <!-- Email -->
                <div class="space-y-2">
                    <label
                        class="block font-label text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1"
                        for="email">Institutional Email</label>
                    <input
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-highest border-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all text-sm font-medium"
                        id="email"
                        placeholder="s.mitchell@medicalcenter.org"
                        type="email" />
                </div>
                <!-- Medical License (Optional) -->
                <div class="space-y-2">
                    <label
                        class="block font-label text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1"
                        for="license">Medical License ID (Optional)</label>
                    <input
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-highest border-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all text-sm font-medium"
                        id="license" placeholder="MD-123456789"
                        type="text" />
                </div>
                <!-- Password Row (Responsive Asymmetry) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label
                            class="block font-label text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1"
                            for="password">Password</label>
                        <input
                            class="w-full px-6 py-4 rounded-lg bg-surface-container-highest border-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all text-sm font-medium"
                            id="password" placeholder="••••••••"
                            type="password" />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block font-label text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1"
                            for="confirm_password">Confirm</label>
                        <input
                            class="w-full px-6 py-4 rounded-lg bg-surface-container-highest border-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all text-sm font-medium"
                            id="confirm_password" placeholder="••••••••"
                            type="password" />
                    </div>
                </div>
                <!-- HIPAA Checkbox -->
                <div class="flex items-start gap-3 py-2">
                    <input
                        class="mt-1 h-5 w-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer"
                        id="hipaa" type="checkbox" />
                    <label
                        class="text-xs text-on-surface-variant leading-relaxed cursor-pointer select-none"
                        for="hipaa">
                        By registering, I certify that I will maintain full
                        <span class="text-primary font-semibold">HIPAA
                            compliance</span> in all data handling and agree to
                        the Clinical Sanctuary terms.
                    </label>
                </div>
                <!-- Submit Button (Signature Texture) -->
                <button
                    class="w-full bg-signature-gradient text-on-primary py-4 rounded-lg font-headline font-bold text-sm tracking-wide shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-200"
                    type="submit">
                    Create Account
                </button>
            </form>
            <div class="mt-12 text-center">
                <p
                    class="text-xs font-label text-on-surface-variant uppercase tracking-widest">
                    Already have an account?
                    <a class="text-primary font-bold ml-2 hover:underline"
                        href="{{ 'login-page' }}">Back to Login</a>
                </p>
            </div>
            <!-- Footer for focused screens -->
            <footer class="mt-16 pt-8 border-t border-surface-container-high">
                <div
                    class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div
                        class="font-label text-[10px] tracking-widest uppercase opacity-40">
                        © 2024 Clinical Sanctuary. HIPAA Compliant Environment.
                    </div>
                    <div class="flex gap-6">
                        <a class="font-label text-[10px] tracking-widest uppercase opacity-40 hover:opacity-100 transition-opacity"
                            href="#">Security Whitepaper</a>
                        <a class="font-label text-[10px] tracking-widest uppercase opacity-40 hover:opacity-100 transition-opacity"
                            href="#">Privacy</a>
                    </div>
                </div>
            </footer>
        </div>
    </main>
</body>

</html>
