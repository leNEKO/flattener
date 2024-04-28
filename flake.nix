{
  description = "PHP development";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    nix-shell.url = "github:loophp/nix-shell";
    systems.url = "github:nix-systems/default";
  };

  outputs = inputs@{ self, flake-parts, systems, ... }: flake-parts.lib.mkFlake { inherit inputs; } {
    systems = import systems;

    perSystem = { config, self', inputs', pkgs, system, lib, ... }:
      let
        php = pkgs.api.buildPhpFromComposer {
          src = inputs.self;
          php = pkgs.php83;
        };
      in
      {
        _module.args.pkgs = import self.inputs.nixpkgs {
          inherit system;
          overlays = [
            inputs.nix-shell.overlays.default
          ];
          config.allowUnfree = true;
        };

        devShells.default = pkgs.mkShellNoCC {
          name = "php-devshell";
          buildInputs = [
            php
            php.packages.composer
            php.packages.phpstan
            php.packages.psalm
            pkgs.nodePackages.intelephense

            # Auto reload tool
            pkgs.watchexec

            # Valgrind profile Gui
            pkgs.graphviz
            pkgs.kcachegrind
          ];

          shellHook = ''
            if ! test -d .nix-shell; then
              mkdir .nix-shell
            fi

            export NIX_SHELL_DIR=$PWD/.nix-shell

            # phpunit
            export PHPUNIT_COVERAGE_PATH=$NIX_SHELL_DIR/phpunit_coverage

            # XDEBUG
            export XDEBUG_OUTPUT_DIR=$NIX_SHELL_DIR/xdebug
            mkdir -p $XDEBUG_OUTPUT_DIR
            export XDEBUG_CONFIG="output_dir=$XDEBUG_OUTPUT_DIR"
          '';
        };
      };
  };
}
