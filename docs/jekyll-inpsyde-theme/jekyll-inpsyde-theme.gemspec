# coding: utf-8

Gem::Specification.new do |spec|
  spec.name          = "jekyll-inpsyde-theme"
  spec.version       = "0.3.3" # TODO
  spec.authors       = ["Patrick Marsceill"] # TODO
  spec.email         = ["patrick.marsceill@gmail.com"]  # TODO

  spec.summary       = %q{A modern, highly customizable, and responsive Jekyll theme for documention with built-in search.}  # TODO
  spec.homepage      = "https://github.com/inpsyde/jekyll-inpsyde-theme"
  spec.license       = "MIT"

  spec.files         = `git ls-files -z`.split("\x0").select { |f| f.match(%r{^(assets|bin|_layouts|_includes|lib|Rakefile|_sass|LICENSE|README)}i) }
  spec.executables   << 'jekyll-inpsyde-theme'

  spec.add_development_dependency "bundler", "~> 2.1.4"
  spec.add_runtime_dependency "jekyll", ">= 3.8.5"
  spec.add_runtime_dependency "jekyll-seo-tag", "~> 2.0"
  spec.add_runtime_dependency "rake", ">= 12.3.1", "< 13.1.0"

end
