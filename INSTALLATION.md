# Installation Guide

This guide explains how to install IGamingLib directly from GitHub without using Packagist.

## Method 1: Using composer.json (Recommended)

Add the repository configuration to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/igaminglib/helpers"
        }
    ],
    "require": {
        "igaminglib/helpers": "dev-main"
    }
}
```

**Important:** 
- The vendor name `igaminglib/helpers` must match the `name` field in the library's `composer.json`
- Use `dev-main` for the main branch, or `dev-develop` for develop branch

Then run:
```bash
composer update
```

Or install directly:
```bash
composer require igaminglib/helpers:dev-main
```

## Method 2: Using composer require with repository flag

You can also add the repository inline:

```bash
composer require igaminglib/helpers:dev-main --repository '{"type":"vcs","url":"https://github.com/igaminglib/helpers"}'
```

## Method 3: Using specific branch or tag

If you want to use a specific branch or tag:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/igaminglib/helpers"
        }
    ],
    "require": {
        "igaminglib/helpers": "dev-feature-branch"
    }
}
```

Or for a specific tag:
```json
{
    "require": {
        "igaminglib/helpers": "v1.0.0"
    }
}
```

## Method 4: Using SSH (if you have access)

If you have SSH access to the repository:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:igaminglib/helpers.git"
        }
    ],
    "require": {
        "igaminglib/helpers": "dev-main"
    }
}
```

## Troubleshooting

### Issue: "Could not find package"

**Solution:** Make sure:
1. The repository URL is correct
2. The repository is public (or you have access)
3. The branch/tag exists
4. The vendor name matches what's in the repository's `composer.json`

### Issue: "No tags found"

**Solution:** Create a tag in your repository:
```bash
git tag v1.0.0
git push origin v1.0.0
```

Or use `dev-main` instead of a version tag.

### Issue: Authentication required

**Solution:** If the repository is private, you'll need to set up authentication:
```bash
composer config github-oauth.github.com YOUR_GITHUB_TOKEN
```

## Updating the Library

To update to the latest version:

```bash
composer update igaminglib/helpers
```

Or to update to a specific branch:
```bash
composer require igaminglib/helpers:dev-main
```

## Example: Complete composer.json

Here's a complete example for a Laravel project:

```json
{
    "name": "mycompany/my-project",
    "type": "project",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/igaminglib/helpers"
        }
    ],
    "require": {
        "php": "^8.2",
        "igaminglib/helpers": "dev-main",
        "laravel/framework": "^12.0"
    }
}
```

## Notes

- The library will be cloned from GitHub, so you need an internet connection
- Updates are pulled from GitHub, not Packagist
- You can use any branch or tag as a version constraint
- The `dev-` prefix is required for branches (e.g., `dev-main`, `dev-develop`)

