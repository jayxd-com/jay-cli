🛠️ Project: Jay-CLI Scaffolding Tool
Owner: Jay (Jawad Multani)

Environment: Ubuntu Linux (XPS 15), TALL Stack (Tailwind, Alpine, Laravel, Livewire)

Goal: A centralized, namespace-based CLI to automate agency-grade Laravel/SaaS provisioning with SSL, Traefik, and unique port management.

1. High-Level Architecture
The system uses a Mirror & Proxy pattern to avoid manual chmod or symlink maintenance.

Source Folder: ~/.../jay-cli/src/ (Where development happens).

System Mirror: /usr/local/bin/jay-cli/ (An exact copy of src).

Master Proxy: /usr/local/bin/jay-cli (The only executable file). It routes commands by calling scripts using bash /path/to/script, bypassing the need for individual execution bits.

2. Directory Structure
Plaintext
jay-cli/
├── install-linux.sh       # Syncs 'src' to /usr/local/bin and updates proxy
└── src/
    ├── lib/               # Shared logic (Source-able utilities)
    │   ├── sail-compose-check  # Detects compose.yaml vs sail-compose.yaml
    │   └── port-check          # Generates unique, non-colliding ports
    └── dev_scaffolding/
        └── laravel/       # The 'laravel' namespace
            ├── create     # Main provisioning script (downloads Laravel)
            └── modules/   # Post-install modules (Octane, Reverb, Sailify)
                ├── sailify   # Configures Traefik SSL & .dev.test domains
                ├── octane    # Installs FrankenPHP/Octane optimization
                └── reverb    # Configures WebSockets with unique ports
3. Core Logic & Guardrails
Port Management: Every project is assigned unique ports (Vite, DB, Redis, Reverb) stored in ~/.jay_cli_ports to prevent collisions during multi-project development.

Production Safety: Scripts explicitly ignore docker-compose.yml. They only target dev-specific files (compose.yaml, etc.) to prevent accidental local overrides of production configurations.

Infrastructure Locking: The SAIL_COMPOSE variable is injected into the .env to force Laravel Sail to use the detected dev-specific YAML file.

Traefik Integration: All projects are automatically routed through a gateway Docker network to support https://project-name.dev.test via a local Traefik v3 container.

4. Command Workflow
jay-cli --help: Scans the dev_scaffolding folder and groups commands by namespace.

jay-cli laravel create [name]: The entry point. Creates the folder, downloads Laravel, and triggers sailify.

jay-cli laravel [module]: Run inside a project folder to add features (e.g., octane, reverb).

5. Current State & Pending Tasks
Current State: The architecture is finalized. Proxy, Installer, and Library (Port/Compose check) are unified.

Active Task: Finalizing the reverb module to use the shared port-check library and inject port 8080 (or its unique equivalent) into compose.yaml.

Instruction for "Antigravity Gemini":
"When assisting Jay, maintain the Mirror & Proxy architecture. Ensure all new modules added to src/dev_scaffolding utilize the shared libraries in src/lib/. Do not assume scripts are executable; always route through the proxy using bash. Keep the help menu grouped and professional."