#!/usr/bin/env python3
"""
Color System Update Script for Coursezy - IMPROVED COLORS
Updates all blade files to use the harmonious color system
"""

import os
import re
from pathlib import Path

# Color mapping: old pattern -> new pattern
COLOR_REPLACEMENTS = {
    # Backgrounds - Combined dark/light
    r'bg-white\s+dark:bg-gray-800': 'bg-light-bg-secondary dark:bg-dark-bg-secondary',
    r'bg-gray-50\s+dark:bg-gray-900': 'bg-light-bg-primary dark:bg-dark-bg-primary',
    r'bg-gray-100\s+dark:bg-gray-900': 'bg-light-bg-primary dark:bg-dark-bg-primary',
    r'bg-gray-100\s+dark:bg-gray-800': 'bg-light-bg-secondary dark:bg-dark-bg-secondary',
    r'bg-white\s+dark:bg-gray-900': 'bg-light-bg-primary dark:bg-dark-bg-primary',
    
    # Text Colors - Combined
    r'text-gray-900\s+dark:text-white': 'text-light-text-primary dark:text-dark-text-primary',
    r'text-gray-900\s+dark:text-gray-100': 'text-light-text-primary dark:text-dark-text-primary',
    r'text-gray-800\s+dark:text-gray-200': 'text-light-text-primary dark:text-dark-text-primary',
    r'text-gray-600\s+dark:text-gray-300': 'text-light-text-secondary dark:text-dark-text-secondary',
    r'text-gray-600\s+dark:text-gray-400': 'text-light-text-secondary dark:text-dark-text-secondary',
    r'text-gray-700\s+dark:text-gray-300': 'text-light-text-secondary dark:text-dark-text-secondary',
    r'text-gray-500\s+dark:text-gray-400': 'text-light-text-muted dark:text-dark-text-muted',
    
    # Borders - Combined
    r'border-gray-200\s+dark:border-gray-700': 'border-light-border-default dark:border-dark-border-default',
    r'border-gray-300\s+dark:border-gray-600': 'border-light-border-default dark:border-dark-border-default',
    r'border-gray-100\s+dark:border-gray-700': 'border-light-border-subtle dark:border-dark-border-subtle',
    
    # Buttons - Indigo to Accent
    r'bg-indigo-600\s+hover:bg-indigo-700\s+dark:bg-indigo-500\s+dark:hover:bg-indigo-600': 'bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90',
    r'bg-indigo-600\s+hover:bg-indigo-700': 'bg-light-accent-secondary hover:bg-light-accent-secondary/90',
    
    # Hover states for backgrounds
    r'hover:bg-gray-50\s+dark:hover:bg-gray-700': 'hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary',
    r'hover:bg-gray-100\s+dark:hover:bg-gray-800': 'hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary',
    
    # Focus rings
    r'focus:ring-indigo-500': 'focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary',
    r'focus:border-indigo-500': 'focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary',
}

# Standalone color replacements (only if not part of a dark: pair)
STANDALONE_REPLACEMENTS = {
    # These will only replace if NOT followed by "dark:"
    r'\bbg-white\b(?!\s+dark:)': 'bg-light-bg-secondary',
    r'\bbg-gray-50\b(?!\s+dark:)': 'bg-light-bg-tertiary',
    r'\bbg-gray-100\b(?!\s+dark:)': 'bg-light-bg-tertiary',
    r'\bbg-gray-800\b(?!\s+dark:)': 'bg-dark-bg-secondary',
    r'\bbg-gray-900\b(?!\s+dark:)': 'bg-dark-bg-primary',
    
    r'\btext-gray-900\b(?!\s+dark:)': 'text-light-text-primary',
    r'\btext-gray-800\b(?!\s+dark:)': 'text-light-text-primary',
    r'\btext-gray-600\b(?!\s+dark:)': 'text-light-text-secondary',
    r'\btext-gray-700\b(?!\s+dark:)': 'text-light-text-secondary',
    r'\btext-gray-500\b(?!\s+dark:)': 'text-light-text-muted',
    r'\btext-gray-400\b(?!\s+dark:)': 'text-dark-text-secondary',
    r'\btext-gray-300\b(?!\s+dark:)': 'text-dark-text-secondary',
    r'\btext-gray-200\b(?!\s+dark:)': 'text-dark-text-primary',
    r'\btext-white\b(?!\s+dark:)': 'text-dark-text-primary',
    
    r'\bborder-gray-200\b(?!\s+dark:)': 'border-light-border-default',
    r'\bborder-gray-300\b(?!\s+dark:)': 'border-light-border-default',
    r'\bborder-gray-600\b(?!\s+dark:)': 'border-dark-border-default',
    r'\bborder-gray-700\b(?!\s+dark:)': 'border-dark-border-default',
    
    r'\bbg-indigo-600\b': 'bg-light-accent-secondary',
    r'\bbg-indigo-500\b': 'bg-dark-accent-secondary',
    r'\bbg-indigo-700\b': 'bg-light-accent-secondary/90',
}

def update_file(filepath):
    """Update colors in a single file"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original_content = content
        
        # Apply combined replacements first
        for pattern, replacement in COLOR_REPLACEMENTS.items():
            content = re.sub(pattern, replacement, content)
        
        # Apply standalone replacements
        for pattern, replacement in STANDALONE_REPLACEMENTS.items():
            content = re.sub(pattern, replacement, content)
        
        # Only write if content changed
        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f"Error processing {filepath}: {e}")
        return False

def main():
    """Main function to update all blade files"""
    views_dir = Path('resources/views')
    
    if not views_dir.exists():
        print("❌ resources/views directory not found!")
        return
    
    print("🎨 Starting color system update...")
    print(f"📁 Scanning {views_dir}...")
    
    blade_files = list(views_dir.rglob('*.blade.php'))
    print(f"📄 Found {len(blade_files)} blade files")
    
    updated_count = 0
    for filepath in blade_files:
        if update_file(filepath):
            print(f"✅ Updated: {filepath}")
            updated_count += 1
        else:
            print(f"⏭️  Skipped: {filepath} (no changes needed)")
    
    print(f"\n✨ Complete! Updated {updated_count} out of {len(blade_files)} files")
    print("📝 Please review changes and test in both light and dark modes")
    print("🔄 Run: npm run build")

if __name__ == '__main__':
    main()
