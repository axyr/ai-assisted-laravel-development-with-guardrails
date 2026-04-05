# Guardrailing AI-Assisted Laravel Development

A minimalistic demonstration of quality guardrails for AI-assisted Laravel development.

Clean architecture, code readability and testability are more important than ever in the age of AI coding assistants.

This project shows you how.

**Read the full article:** [Guardrailing AI-Assisted Laravel Development](https://martijnvannieuwenhoven.com/guardrailing-ai-assisted-laravel-development)

## The Problem

AI coding assistants like Claude Code, Cursor, and GitHub Copilot generate code fast. Very fast.

But speed without quality is just technical debt at scale.

27% of AI-generated code contains vulnerabilities. Teams without proper guardrails often see negative ROI because they spend more time fixing AI-generated bugs than writing code manually.

The solution is not to avoid AI tools. The solution is to put proper guardrails in place.

## What Are Guardrails?

Guardrails are automated controls that ensure your code meets quality standards - whether written by humans or AI.

The most important guardrail is tests. Preferably written before production code.

But tests alone do not check **how** code is written. You can have perfectly passing tests for an unreadable, unmaintainable mess.

That is where quality tools come in.

## The Guardrail Stack

This project uses four layers of quality enforcement:

| Tool | Purpose | Configuration |
|------|---------|---------------|
| **Laravel Pint** | Code formatting | PER standard + strict types |
| **Larastan** | Static analysis | PHPStan level 9 with Laravel awareness |
| **PHPMD** | Complexity analysis | CC≤5, NPath≤200, methods≤30 lines |
| **Pest** | Testing + architecture rules | Enforces structural standards |

### Why These Tools?

**Laravel Pint** is Laravel's opinionated code formatter. Zero configuration needed. It enforces the PER coding standard plus strict type declarations on every PHP file.

**Larastan** combines PHPStan's type checking with deep Laravel knowledge. It understands facades, Eloquent relationships, and dependency injection. Running at level 9 catches type mismatches before they become runtime errors.

**PHPMD** detects code smells and enforces complexity limits. AI assistants love writing long, complex methods. PHPMD stops that cold. Cyclomatic complexity must stay below 5, methods cannot exceed 30 lines, and classes cannot have more than 4 parameters without using parameter objects.

**Pest** provides clean, readable tests and architecture testing. Architecture tests enforce rules like "all source files use strict types" and "models do not use facades directly". These rules prevent architectural drift.

## Enforcement Layers

Quality tools are useless if you have to run them manually.

This project enforces guardrails at three levels:

### 1. Local Development (Claude Code Hooks)

When using Claude Code, hooks run automatically after code changes:

- **After editing PHP files**: Pint formats the code
- **After editing app/ files**: PHPStan checks for type errors
- **After editing tests**: Pest runs the modified test file

Hooks also block dangerous operations:
- No `git push --force`
- No `git reset --hard`
- No accidental `rm -rf vendor`

Configuration: `.claude/settings.json`

### 2. Pre-Commit (Git Hooks)

Before any commit, a pre-commit hook runs the full quality suite:

1. Code style check (Pint)
2. Static analysis (Larastan)
3. Complexity check (PHPMD)
4. All tests (Pest)

If any check fails, the commit is blocked.

The hook auto-installs via Composer post-install.

Configuration: `hooks/pre-commit`

### 3. CI/CD (GitHub Actions)

The final enforcement layer runs on every push and pull request.

Five separate jobs run in parallel:
- **Tests** (PHP 8.3 and 8.4)
- **Static Analysis** (Larastan)
- **Code Style** (Pint)
- **Complexity** (PHPMD)
- **Architecture Tests** (Pest)

No code reaches production without passing all checks.

Configuration: `.github/workflows/ci.yml`

## Architecture Tests

The architecture tests in `tests/Arch/ArchitectureTest.php` enforce structural rules:

**Strict Types Everywhere**
```php
arch('all source files use strict types')
    ->expect('App')
    ->toUseStrictTypes();
```

**Laravel Conventions**
- Models extend `Illuminate\Database\Eloquent\Model`
- Controllers extend the base controller
- Form requests extend `FormRequest`
- Policies end with `Policy` suffix
- Jobs implement `ShouldQueue`

**Code Quality**
- No debug statements (`dd`, `dump`, `var_dump`) in production code
- Models do not use facades (dependency injection instead)
- Maximum 10 public methods per class (avoid god objects)

**Security**
- No raw SQL (use query builder or Eloquent)

These tests run automatically with the rest of your test suite.

## Quick Start

**Requirements:** PHP 8.4+

Clone and install:

```bash
git clone <repository-url>
cd ai-assisted-laravel-development-with-guardrails
composer install
cp .env.example .env
php artisan key:generate
```

Run quality checks:

```bash
composer quality          # Run all checks
composer format           # Fix code style
composer analyse          # Static analysis
composer complexity       # Check complexity
composer test             # Run tests
composer test:arch        # Architecture tests only
```

The pre-commit hook installs automatically. Every commit now requires passing all quality checks.

## Using with AI Assistants

### Claude Code

The `.claude/settings.json` configuration provides:

- Auto-formatting after every PHP file edit
- Instant PHPStan feedback on app/ changes
- Automatic test execution after test edits
- Session start reminders about quality tools
- Protection against destructive git operations

### Cursor / GitHub Copilot

Add this project's conventions to your AI assistant's context:

- Strict types on all PHP files (`declare(strict_types=1)`)
- PER coding standard via Pint
- Type hints on all methods and parameters
- Maximum cyclomatic complexity of 5
- Maximum method length of 30 lines
- Maximum 4 parameters per method

Run `composer quality` frequently during AI-assisted development sessions.

## Configuration Files

All guardrails are configured through these files:

- `pint.json` - Code formatting rules
- `phpstan.neon.dist` - Static analysis configuration
- `phpmd.xml` - Complexity and code smell rules
- `tests/Arch/ArchitectureTest.php` - Architecture enforcement
- `.claude/settings.json` - Claude Code automation
- `hooks/pre-commit` - Git pre-commit checks
- `.github/workflows/ci.yml` - CI pipeline

## The Most Important Guardrail

Is still you.

I read every line of code before committing to the repository.

Claude sometimes wraps code in try/catch blocks when a task takes too long. It might write functionality from scratch that already exists elsewhere. All automated guardrails pass, but something is clearly wrong.

That is why readable code matters - even in the AI era.

Automated guardrails improve code quality dramatically. But if you are serious about production environments, you still need to review what your assistant generated.

## Why This Matters

Teams with strong guardrails see **3x productivity gains** from AI tools.

Teams without them often see **negative ROI** - they spend more time fixing AI bugs than writing code manually.

The difference is not the AI tool. The difference is the guardrails.

## Learn More

This demonstration accompanies the article **[Guardrailing AI-Assisted Laravel Development](https://martijnvannieuwenhoven.com/guardrailing-ai-assisted-laravel-development)** by Martijn van Nieuwenhoven.

### Research & Tools

**AI-Assisted Development:**
- [AI Code Quality in 2026](https://tfir.io/ai-code-quality-2026-guardrails/) - Essential guardrails
- [Shopify's AI-First Engineering Playbook](https://www.bvp.com/atlas/inside-shopifys-ai-first-engineering-playbook) - Production practices
- [CodeScene: Guardrails for AI Coding](https://codescene.com/blog/implement-guardrails-for-ai-assisted-coding) - Metrics and validation

**Laravel AI Tools:**
- [Laravel Boost Documentation](https://laravel.com/docs/13.x/boost) - Official Laravel AI integration
- [Laravel AI Development Guide](https://laravel.com/docs/13.x/ai) - Best practices
- [Freek's 2026 Laravel + AI Setup](https://freek.dev/3006-my-current-setup-for-laravel-php-and-ai-development-2026-edition) - Real-world configuration

**Quality Tools:**
- [Laravel Pint](https://laravel.com/docs/pint) - Code formatting
- [Larastan](https://github.com/larastan/larastan) - Laravel-aware static analysis
- [PHPMD](https://phpmd.org/) - PHP Mess Detector
- [Pest Architecture Testing](https://pestphp.com/docs/arch-testing) - Structural enforcement

## License

MIT

## Author

Built by [Martijn van Nieuwenhoven](https://martijnvannieuwenhoven.com) - Laravel developer specializing in AI integrations and quality tooling.
