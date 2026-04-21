# 🦁 Jay-CLI

**Agency-Grade Laravel Scaffolding Tool**

Jay-CLI is a powerful, namespace-based command-line interface designed to automate the provisioning of agency-quality Laravel applications. It handles SSL, Traefik routing, unique port management, and batch module installation out of the box.

## ✨ Features

- **🚀 Instant Provisioning**: Create a full Laravel/Sail stack with one command.
- **🔒 Local SSL**: Automatic Traefik v3 integration for `https://project.dev.test` domains.
- **🔌 Unique Port Management**: Automatically assigns non-colliding ports for Vite, DB, Redis, and Reverb.
- **🛠️ Module System**: Batch install Horizon, Octane, and Reverb with smart dependency detection.
- **🩺 Health Check**: Built-in `doctor` command to verify your installation and script links.
- **🌲 Professional Help**: Clean, tree-style help menu with detailed command information.

## 📦 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/jayxd/jay-cli.git
   cd jay-cli
   ```

2. Run the installer:
   ```bash
   sudo ./install-linux.sh
   ```

## 🚀 Usage

### Create a new project
```bash
jay-cli laravel create my-project
```
*Use `--solo` to install directly in the root folder instead of `backend/`.*

### Install Modules
```bash
# Inside your project folder
jay-cli laravel install-modules
```
*Options:*
- `--with=boost,octane`: Install specific modules.
- `--force`: Re-install even if already detected.
- `--list`: See all available modules.

### Diagnostics & Maintenance
```bash
jay-cli doctor      # Run a health check
jay-cli uninstall   # Safely remove Jay-CLI from your system
```

## 📂 Project Structure

```text
jay-cli/
├── install-linux.sh    # System-wide installer
├── src/
│   ├── lib/            # Shared logic (Port management, etc.)
│   └── dev_scaffolding/# Command namespaces (Laravel, Doctor, etc.)
```

## 📜 Architecture

Jay-CLI uses a **Mirror & Proxy** pattern:
- **Mirror**: Your `src/` folder is mirrored to `/opt/jay-cli/`.
- **Proxy**: A standalone executable at `/usr/local/bin/jay-cli` routes commands dynamically to the mirror.

This ensures you can develop the CLI locally and have changes reflected across your system instantly after running the installer.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---
Created by **Jay (Jawad Multani)**
