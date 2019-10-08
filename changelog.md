# GreenLight Auth Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.1.0 - 2019-10-08
### Added
- Ability to change a config variable at run-time.

## 1.0.0 - 2019-10-08
### Added
- php 7 compatability

### Changed 
- PurgeAndReplace Flag now a configurable option - defaults to false
- Product conditions now constants.
- Application name now a configurable option and is required.
- MWSProduct::toArray() simply casts the current object rather than building a new array.

### Removed
- MWSProduct::__set() - wasnt actually setting anything..