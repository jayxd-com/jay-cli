#!/bin/bash
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

# Read version
VERSION_RAW=$(cat "$SOURCE_DIR/src/VERSION" 2>/dev/null || echo "0.0")
VERSION_DISPLAY="v$VERSION_RAW"
[[ "$VERSION_RAW" == 0* ]] && VERSION_DISPLAY="$VERSION_DISPLAY-beta"

echo "------------------------------------------------"
echo "🌐 JAY-CLI $VERSION_DISPLAY SYSTEM SYNC"
echo "------------------------------------------------"
echo "📦 Mirroring src to /opt/jay-cli..."

# 1. Define Paths
SYSTEM_DIR="/opt/jay-cli"
EXECUTABLE_PATH="/usr/local/bin/jay-cli"

# 2. Clear and recreate the system directory
sudo rm -rf "$SYSTEM_DIR"
sudo mkdir -p "$SYSTEM_DIR"

# 3. Copy the contents of your local src folder to the system
sudo cp -r "$SOURCE_DIR/src/." "$SYSTEM_DIR/"

echo "🔗 Updating global proxy..."
sudo tee /usr/local/bin/jay-cli-proxy > /dev/null << 'EOF'
#!/bin/bash
# The directory where all your scaffolding folders live
ROOT_DIR="/opt/jay-cli/dev_scaffolding"

# Namespace Detection: Check if $1 matches a folder in dev_scaffolding
if [ -d "$ROOT_DIR/$1" ] && [ -n "$1" ]; then
    BASE_DIR="$ROOT_DIR/$1"
    shift
else
    BASE_DIR="$ROOT_DIR"
fi

COMMAND=$1
shift

# --- DYNAMIC TREE HELP ---
if [[ "$COMMAND" == "--help" ]] || [[ -z "$COMMAND" ]]; then
    VERSION_RAW=$(cat "/opt/jay-cli/VERSION" 2>/dev/null || echo "0.0")
    VERSION_DISPLAY="v$VERSION_RAW"
    [[ "$VERSION_RAW" == 0* ]] && VERSION_DISPLAY="$VERSION_DISPLAY-beta"

    echo "🦁 Jay-CLI $VERSION_DISPLAY | Agency Scaffolding Tool"
    echo "Usage: jay-cli [namespace] [command]"
    echo "--------------------------------------------"
    
    # 1. GLOBAL UTILITIES (Root level scripts like 'doctor')
    globals=($(ls -p "$ROOT_DIR" 2>/dev/null | grep -v /))
    if [ ${#globals[@]} -gt 0 ]; then
        echo "🛠️  [Global Utilities]"
        for i in "${!globals[@]}"; do
            cmd="${globals[$i]}"
            cmd_path="${ROOT_DIR}/$cmd"
            connector="├──"
            [ $((i + 1)) -eq ${#globals[@]} ] && connector="└──"
            echo "  $connector $cmd"
            grep "^# Info:" "$cmd_path" | head -n 1 | cut -d':' -f2- | sed "s|^ |      └── |"
        done
        echo ""
    fi

    # 2. NAMESPACED COMMANDS (Folders)
    for dir in "$ROOT_DIR"/*/; do
        [ -d "$dir" ] || continue
        NAMESPACE=$(basename "$dir")
        echo "📂 [$NAMESPACE]"
        
        cmds=($(ls -p "$dir" 2>/dev/null | grep -v /))
        count=${#cmds[@]}
        
        for i in "${!cmds[@]}"; do
            cmd="${cmds[$i]}"
            cmd_path="${dir}${cmd}"
            
            if [ $((i + 1)) -eq $count ]; then
                connector="└──"
                pipe="    "
            else
                connector="├──"
                pipe="│   "
            fi
            
            echo "  $connector $cmd"
            # Format Info lines with tree-style indentation
            grep "^# Info:" "$cmd_path" | cut -d':' -f2- | sed "s|^ |  $pipe └── |"
        done
        echo ""
    done
    exit 0
fi

# --- UNIVERSAL ROUTING ---
if [ -f "$BASE_DIR/$COMMAND" ]; then
    bash "$BASE_DIR/$COMMAND" "$@"
elif [ -f "$BASE_DIR/modules/$COMMAND" ]; then
    bash "$BASE_DIR/modules/$COMMAND" "$@"
else
    echo "❌ Error: Command '$COMMAND' not recognized."
    exit 1
fi
EOF

# 4. Finalize the executable
if [ -d "$EXECUTABLE_PATH" ]; then
    sudo rm -rf "$EXECUTABLE_PATH"
fi

sudo chmod +x /usr/local/bin/jay-cli-proxy
sudo mv /usr/local/bin/jay-cli-proxy "$EXECUTABLE_PATH"

echo "🚀 System Sync Complete."
echo "👉 Command: jay-cli --help"
echo "------------------------------------------------"
