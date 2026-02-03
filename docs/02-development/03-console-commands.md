# Console Commands

Nadi provides four Yii console commands for setup and management.

## nadi:install

Publishes the default configuration and installs the shipper binary.

```bash
yii nadi:install
```

This command:

1. Copies the default config to `config/packages/nadi/params.php`
2. Installs the shipper binary
3. Displays next steps for setup

## nadi:test

Tests the connection to the Nadi backend.

```bash
yii nadi:test
```

Calls `transporter.test()` and reports whether the connection was successful or failed.

## nadi:verify

Verifies that the current configuration is valid.

```bash
yii nadi:verify
```

Checks:

- Whether monitoring is enabled
- The selected driver is valid
- Required credentials are present (for the HTTP driver)
- The transporter can be initialized

## nadi:update-shipper

Reinstalls or updates the shipper binary.

```bash
yii nadi:update-shipper
```

## Next Steps

- [Getting Started](01-getting-started.md)
- [Configuration Parameters](../03-configuration/01-parameters.md)
